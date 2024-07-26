<?php

namespace App\Http\Controllers;

use App\Models\BusquedaProgramada;
use App\Models\NegativeListsMeta;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Illuminate\Http\Request;

use DB;


class ProgramadaController extends Controller
{
    // addProgramada requerst, tambein se enviara por form-data infonombres y infoapellidos
    public function addProgramada(Request $request)
    {
        try {
            $infonombres = $request->infonombres;
            $infoapellidos = $request->infoapellidos;
            $iduser = $request->iduser;
            $estado = 0;

            if (empty($infonombres) || empty($infoapellidos) || empty($iduser)) {
                return response()->json(['status' => false, 'message' => 'Todos los campos son requeridos']);
            }



            $busqueda = new BusquedaProgramada();
            $busqueda->infonombres = $infonombres;
            $busqueda->infoapellidos = $infoapellidos;
            $busqueda->iduser = $iduser;
            $busqueda->estado = $estado;

            if (BusquedaProgramada::where('infonombres', $infonombres)->where('infoapellidos', $infoapellidos)->where('iduser', $iduser)->exists()) {
                return response()->json(['status' => false, 'message' => 'La busqueda programada ya existe para esta persona']);
            }

            $busqueda->save();
            return response()->json(['status' =>  true, 'message' => 'Busqueda programada agregada correctamente']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error al agregar la busqueda programada']);
        }
    }

    public function listProgramadas(Request $request)
    {
        $start = $request->start;
        $length = $request->length;

        $programadas = BusquedaProgramada::orderBy('id', 'desc')->skip($start)->take($length)->get();
        $total = BusquedaProgramada::count();
        $data = [];
        foreach ($programadas as $index => $programada) {
            $data[] = [
                'rn' => $start + $index + 1,
                'id' => $programada->ID,
                'infonombres' => $programada->INFONOMBRES,
                'infoapellidos' => $programada->INFOAPELLIDOS,
                'iduser' => $programada->IDUSER,
                'estado' => $programada->ESTADO
            ];
        }


        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ]);
    }

    public function deleteProgramada(Request $request)
    {
        try {
            //code...
            $id = $request->iduser;
            $id = intval($id);
            $programada = BusquedaProgramada::find($id);
            if ($programada) {
                $programada->delete();
                return response()->json(['status' => true, 'message' => 'Busqueda programada eliminada correctamente']);
            }
            return response()->json(['status' => false, 'message' => 'Error al eliminar la busqueda programada']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => false, 'message' => 'Error al eliminar la busqueda programada']);
        }
    }

    public function notifyProgramada()
    {
        try {
            //code...+
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ccarbajalmt0520@gmail.com';
            $mail->Password = 'qcigvfwwdyrwelib';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $users = DB::table('wp_users as A')
                ->join('tb_busqueda_programada as B', 'B.IDUSER', '=', 'A.ID')
                ->where('B.ESTADO', 0)
                ->select('A.ID', 'A.user_email', 'A.display_name')
                ->groupBy('A.ID', 'A.user_email', 'A.display_name')
                ->get();

            foreach ($users as $user) {
                $infos = DB::table('tb_notify_info as A')
                    ->join('tbl_info as B', 'B.infoid', '=', 'A.IDINFO')
                    ->join('tb_busqueda_programada as C', function ($join) use ($user) {
                        $join->on('C.INFONOMBRES', '=', 'B.infonombres')
                            ->on('C.INFOAPELLIDOS', '=', 'B.infoapellidos')
                            ->on('C.IDUSER', '=', DB::raw($user->ID))
                            ->on('C.ESTADO', '=', DB::raw(0));
                    })->select(
                        'B.infoid',
                        'B.infotipo',
                        'B.infoapellidos',
                        'B.infonombres',
                        'B.infoprog',
                        'B.infocargo',
                        'B.infolink',
                        'B.infoalias',
                        'B.infotipodocumento',
                        'B.infoidentifica',
                        'B.infopasaporte',
                        'B.infonacionalidad',
                        'B.infogenero',
                        'B.infomas',
                        'B.infofecha',
                        'B.infolugar',
                        'C.ID'
                    )
                    ->groupBy(
                        'B.infoid',
                        'B.infotipo',
                        'B.infoapellidos',
                        'B.infonombres',
                        'B.infoprog',
                        'B.infocargo',
                        'B.infolink',
                        'B.infoalias',
                        'B.infotipodocumento',
                        'B.infoidentifica',
                        'B.infopasaporte',
                        'B.infonacionalidad',
                        'B.infogenero',
                        'B.infomas',
                        'B.infofecha',
                        'B.infolugar',
                        'C.ID'
                    )
                    ->get();


                if (count($infos) == 0) {
                    continue;
                }

                $htmlBody = '<h1>Información Detallada</h1>';
                foreach ($infos as $info) {
                    $htmlBody .= '<h2>INFO CÓDIGO: ' . $info->infoid . '</h2>';
                    $htmlBody .= '<p>Tipo: ' . $info->infotipo . '</p>';
                    $htmlBody .= '<p>Nombres: ' . $info->infoapellidos . ' ' . $info->infonombres . '</p>';
                    $htmlBody .= '<p>Programa: ' . $info->infoprog . '</p>';
                    $htmlBody .= '<p>Cargo: ' . $info->infocargo . '</p>';
                    $htmlBody .= '<br>';

                    // ACTUALIZAR ESTADO
                    $busqueda = BusquedaProgramada::find($info->ID);
                    $busqueda->ESTADO = 1;
                    $busqueda->save();

                    $busqueda = new NegativeListsMeta();
                    $busqueda->busquedauser = $user->ID;
                    $busqueda->busquedainfo = $info->infoid;
                    $busqueda->busquedafecha = date('Y-m-d H:i:s');
                    $busqueda->save();

                }


                $mail->setFrom('ccarbajalmt0520@gmail.com', 'Complytools');
                $mail->addAddress($user->user_email, $user->display_name);
                $mail->isHTML(true);
                $fechaHoy = date('Y-m-d');
                $mail->Subject = 'Actualización de Lista Negativa - ' . $fechaHoy;
                $mail->Body = $htmlBody;
                $mail->AltBody = strip_tags($htmlBody);

                $mail->send();
            }

            DB::table('tb_notify_info')->delete();

            return response()->json(['status' => true, 'message' => 'Se ha notificado a los usuarios correctamente']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error al enviar el correo: ' . $mail->ErrorInfo]);
        }
    }
}
