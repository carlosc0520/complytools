<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use PHPMailer\PHPMailer\PHPMailer;
use DB;

class MantenimientosController extends Controller
{
    public function listMantenimientos($idempresa)
    {
        // listar datos de tbd_mantenimientos, mientras idempresa 
        try {
            $mantenimientos = DB::table('tbd_mantenimiento')
                ->where('IDEMPRESA', $idempresa)
                ->where('ESTADO', 'A')
                ->get();
            return response()->json($mantenimientos, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addMantenimiento(Request $request, $idempresa)
    {
        try {
            $data = $request->input('data');
            $files = $request->file('files');
            $dataArray = json_decode($data, true);
    
            if (!$dataArray || !isset($dataArray['IDEMPRESA']) || !isset($dataArray['CAUSA'])) {
                throw new \Exception('Datos faltantes en la solicitud.');
            }
    
            $IDENTIFICADOR = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
    
            $denunciaId = DB::table('tbd_denuncias_empresa')->insertGetId([
                'IDEMPRESA' => $dataArray['IDEMPRESA'],
                'RECEPTOR' => $dataArray['RECEPTOR'] ?? null,
                'IDENTIFICADOR' => $IDENTIFICADOR,
                'CAUSA' => $dataArray['CAUSA'],
                'RELACION' => $dataArray['RELACION'] ?? null,
                'DETALLE' => $dataArray['DETALLE'] ?? null,
                'DENUNCIANTE' => $dataArray['DENUNCIANTE'] ?? null,
                'DOCUMENTO' => $dataArray['DOCUMENTO'] ?? null,
                'TELEFONO' => $dataArray['TELEFONO'] ?? null,
                'CORREO' => $dataArray['CORREO'] ?? null,
                'FINCIDENCIA' => $dataArray['FINCIDENCIA'] ?? null,
                'ANONIMA' => $dataArray['ANONIMA'] ?? 0,
                'RAZONRECEPTOR' => $dataArray['RAZONRECEPTOR'] ?? null,
                'FAGREGADO' => now(),
                'ESTADO' => 'A',
            ]);
    
            if (isset($dataArray['INVOLUCRADOS'])) {
                foreach ($dataArray['INVOLUCRADOS'] as $involucrado) {
                    DB::table('tbd_involucrados')->insert([
                        'IDDENUNCIA' => $denunciaId,
                        'TIPOPERSONA' => $involucrado['TIPOPERSONA'] ?? null,
                        'ROL' => $involucrado['ROL'] ?? null,
                        'IDENTIFICADOR' => $involucrado['IDENTIFICADOR'] ?? null,
                        'NOMBRE' => $involucrado['NOMBRE'] ?? null,
                        'CARGO' => $involucrado['CARGO'] ?? null,
                        'EMAIL' => $involucrado['EMAIL'] ?? null,
                        'TELEFONO' => $involucrado['TELEFONO'] ?? null,
                        'COMENTARIO' => $involucrado['COMENTARIO'] ?? null,
                        'FAGREGADO' => now(),
                        'ESTADO' => 'A',
                    ]);
                }
            }
    
            if ($files) {
                $INFOARCHIVOS = $dataArray['ARCHIVOS'] ?? [];
                $path = 'denuncias/files/' . $idempresa . '/' . $denunciaId;
    
                foreach ($files as $file) {
                    $index = collect($INFOARCHIVOS)->search(function ($archivo) use ($file) {
                        return $archivo['NOMBRE'] === $file->getClientOriginalName();
                    });
    
                    $COMENTARIO = $index !== false ? $INFOARCHIVOS[$index]['COMENTARIO'] : '';
    
                    Storage::disk('public')->makeDirectory($path);
    
                    $filePath = $file->store($path, 'public');
    
                    DB::table('tbd_files')->insert([
                        'IDDENUNCIA' => $denunciaId,
                        'NOMBRE' => $file->getClientOriginalName(),
                        'FILE' => $filePath,
                        'COMENTARIO' => $COMENTARIO,
                        'FAGREGADO' => now(),
                    ]);
                }
            }
    
            $receptor = User::find($dataArray['RECEPTOR'] ?? null);
    
            if (!empty($dataArray['CORREO'])) {
                $this->sendEmailNotification($dataArray['CORREO'], $dataArray, $denunciaId, $IDENTIFICADOR);
            }


            if ($receptor && $receptor->user_email) {
                $receptor->user_email = str_replace(["\r", "\n"], '', $receptor->user_email);
                $this->sendEmailNotification($receptor->user_email, $dataArray, $denunciaId, $IDENTIFICADOR);
            }
    
            return response()->json([
                'message' => 'Datos recibidos e insertados correctamente',
                'status' => true,
                'DENUNCIAID' => $denunciaId,
                'IDENTIFICADOR' => $IDENTIFICADOR,
            ], 201);
    
        } catch (\Exception $e) {
            Log::error('Error al insertar datos: ' . $e->getMessage());
    
            return response()->json([
                'message' => 'Error al procesar la solicitud',
                'error' => $e->getMessage(),
                'status' => false,
            ], 500);
        }
    }
    
    private function sendEmailNotification($email, $dataArray, $denunciaId, $IDENTIFICADOR)
    {
        $html = '<h1>Se ha registrado una nueva denuncia</h1>';
        $html .= '<p>Denunciante: ' . (empty($dataArray['DENUNCIANTE']) ? 'Anónimo' : $dataArray['DENUNCIANTE'])  . '</p>';
        $html .= '<p>El pin asignado es: ' . $denunciaId . '</p>';
        $html .= '<p>Identificador: ' . $IDENTIFICADOR . '</p>';
        $html .= '<p>Para más detalles, ingresa al sistema.</p>';
        // $html .= '<img src="https://soft.toolscomply.com/assets/logos/logo-light.png" alt="Tools Comply" />';
    
        $headers = "From: central@toolscomply.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
        $fechaHoy = date('Y-m-d');
        mail($email, 'Nueva denuncia registrada - ' . $fechaHoy, $html, $headers);
    }
    
    public function getDenuncia(Request $request)
    {
        try {
            $identificador = $request->input('IDENTIFICADOR');
            $pin = $request->input('PIN');

            // unir tabla con tbd_mantenimiento union RELACION = ID, CAUSA = ID
            $denuncia = DB::table('tbd_denuncias_empresa')
                ->leftJoin('tbd_mantenimiento as causa_mantenimiento', 'tbd_denuncias_empresa.CAUSA', '=', 'causa_mantenimiento.ID')
                ->leftJoin('tbd_mantenimiento as relacion_mantenimiento', 'tbd_denuncias_empresa.RELACION', '=', 'relacion_mantenimiento.ID')
                ->leftJoin('tbd_empresa', 'tbd_denuncias_empresa.IDEMPRESA', '=', 'tbd_empresa.ID')
                ->select(
                    'tbd_denuncias_empresa.*',
                    'causa_mantenimiento.DESCRIPCION as CAUSA',
                    'relacion_mantenimiento.DESCRIPCION as RELACION',
                    'tbd_empresa.empresa as EMPRESA'
                )
                ->where('tbd_denuncias_empresa.IDENTIFICADOR', $pin)
                ->where('tbd_denuncias_empresa.ID', $identificador)
                ->first();

            if (!$denuncia) {
                return response()->json(['message' => 'No se encontró la denuncia', 'status' => false], 404);
            }

            $involucrados = DB::table('tbd_involucrados')
                ->where('IDDENUNCIA', $denuncia->ID)
                ->get();

            $files = DB::table('tbd_files')
                ->where('IDDENUNCIA', $denuncia->ID)
                ->get();

            return response()->json([
                'denuncia' => $denuncia,
                'involucrados' => $involucrados,
                'files' => $files,
                'status' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'status' => false, 'message' => 'Error al obtener la denuncia'], 500);
        }
    }

    public function addTestigo(Request $request)
    {
        try {
            $params = $request->input('data');
            $data = json_decode($params, true);

            $involucradoId = DB::table('tbd_involucrados')->insertGetId([
                'IDDENUNCIA' => $data['IDDENUNCIA'],
                'TIPOPERSONA' => $data['TIPOPERSONA'],
                'ROL' => $data['ROL'],
                'IDENTIFICADOR' => $data['IDENTIFICADOR'],
                'NOMBRE' => $data['NOMBRE'],
                'CARGO' => $data['CARGO'],
                'EMAIL' => $data['EMAIL'],
                'TELEFONO' => $data['TELEFONO'],
                'COMENTARIO' => $data['COMENTARIO'],
                'FAGREGADO' => now(),
                'ESTADO' => 'A',
            ]);



            if (!$involucradoId) {
                return response()->json(['message' => 'Error al agregar testigo', 'status' => false], 500);
            }

            return response()->json([
                'message' => 'Testigo agregado correctamente',
                'status' => true
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'status' => false, 'message' => 'Error al agregar testigo'], 500);
        }
    }

    public function addDocumento(Request $request){
        try {
            $params = $request->input('data');
            $data = json_decode($params, true);

            $file = $request->file('file');

            $path = 'denuncias/files/' . $data['IDEMPRESA'] . '/' . $data['IDDENUNCIA'];

            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            $filePath = $file->store($path, 'public');

            $fileId = DB::table('tbd_files')->insertGetId([
                'IDDENUNCIA' => $data['IDDENUNCIA'],
                'NOMBRE' => $file->getClientOriginalName(),
                'FILE' => $filePath,
                'COMENTARIO' => $data['COMENTARIO'],
                'FAGREGADO' => now(),
            ]);

            if (!$fileId) {
                return response()->json(['message' => 'Error al agregar documento', 'status' => false], 500);
            }

            return response()->json([
                'message' => 'Documento agregado correctamente',
                'status' => true
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'status' => false, 'message' => 'Error al agregar documento'], 500);
        }
    }

    public function getOficiales(Request $request, $idempresa)
    {
        try {
            $oficiales = DB::table('tbd_oficiales')
                ->join('wp_users', 'tbd_oficiales.IDUSER', '=', 'wp_users.ID')
                ->select(
                    'wp_users.ID',
                    'tbd_oficiales.OFICIAL',
                    'wp_users.display_name',
                    'wp_users.lastname',
                    'wp_users.user_email'
                )
                ->where('wp_users.COMPANY', $idempresa)
                ->where('tbd_oficiales.ESTADO', 'A')
                ->get();

            return response()->json([
                'message' => 'Oficiales obtenidos correctamente',
                'oficiales' => $oficiales,
                'status' => true
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => false, 'message' => 'Error al obtener oficiales'], 500);
        }
    }

    public function getDoc($iddoc)
    {
        try {
            $file = DB::table('tbd_files')
                ->where('ID', $iddoc)
                ->first();

            if (!$file) {
                return response()->json(['message' => 'No se encontró el archivo', 'status' => false], 404);
            }

            $path = storage_path('app/public/' . $file->FILE);

            return response()->download($path, $file->NOMBRE);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => false, 'message' => 'Error al obtener el archivo'], 500);
        }
    }
}
