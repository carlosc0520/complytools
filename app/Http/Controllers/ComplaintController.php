<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Company;
use App\Models\Complaint;
use App\Models\ComplaintCompany;
use App\Models\ComplaintRelation;
use App\Models\ComplaintFile;
use App\Models\ComplaintHistorial;

use Session;
use PDF;
use DB;

class ComplaintController extends Controller {

  public function viewList() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $userId = Session::get("loginId");

    return view("pages.complaints.index", compact('userId'));
  }

  public function dtTable(Request $request) {
    /* SELECT
        dn.id,
        dn.code,
        dn.tbd_causa_id as idcausa,
        dn.tbd_relacion_id as idrelacion,
        cs.descrip as causa,
        rl.descrip as relacion,
        st.code as estadocode,
        st.alias as estadoalias,
        st.name as estado,
        dn.fecha as created_at,
        dn.fecha_cierre as closed_at,
        CONCAT(
          '<span style=''color: grey; margin-right: 5px;'' class=''fa fa-eye hoverActionIcon'' title=''Ver'' onclick=''showModalDetails(',dn.id,')''></span>',
          '<span style=''color: grey; margin-left: 5px; margin-right: 5px;'' class=''fa fa-print hoverActionIcon'' title=''Imprimir'' onclick=''showModalPrinter(',dn.id,')''></span>',
          '<span style=''color: grey; margin-left: 5px;'' class=''fas fa-share-square hoverActionIcon'' title=''Asignar'' onclick=''showModalAssign(',dn.id,')''></span>'
        ) AS ACTIONS
      FROM tbd_denuncia dn
      INNER JOIN tbd_causa cs ON cs.id = dn.tbd_causa_id
      INNER JOIN tbd_relacion rl ON rl.id = dn.tbd_relacion_id
      LEFT JOIN tbd_estado st ON st.code = dn.estado
      WHERE dn.tbd_empresa_id = {$idCompany}
      ORDER BY id DESC */
    $userId = $request->userId;
    $user = User::find($userId);
    $company = ComplaintCompany::where('wp_users_id', '=', $userId)->first();

    $data = [];
    if ($company) {
      $role = $user->role;
      if ($role === 'admin') {
        $data = Complaint::select(
          "tbd_denuncia.id",
          "tbd_denuncia.code",
          "tbd_denuncia.fecha AS created_at",
          "tbd_denuncia.fecha_cierre AS closed_at",
          "tbd_denuncia.descrip AS description",
          "tbd_denuncia.nombre AS name",
          "tbd_denuncia.dni AS dni",
          "tbd_denuncia.telf AS cellphone",
          "tbd_denuncia.correo AS email",
          "tbd_denuncia.terms",
          "tbd_denuncia.sustento AS file",
          "tbd_causa.descrip AS reason",
          "tbd_denuncia.tbd_causa_id AS reasonId",
          "tbd_relacion.descrip AS relation",
          "tbd_denuncia.tbd_relacion_id AS relationId",
          "tbd_denuncia.tbd_empresa_id AS companyId",
          "tbd_denuncia.tbd_empresauser_id AS companyuserId",
          "tbd_estado.code AS state",
          "tbd_estado.name AS state_name"
        )
        ->join('tbd_causa', 'tbd_causa.id', '=', 'tbd_denuncia.tbd_causa_id')
        ->join('tbd_relacion', 'tbd_relacion.id', '=', 'tbd_denuncia.tbd_relacion_id')
        ->leftjoin('tbd_estado', 'tbd_estado.code', '=', 'tbd_denuncia.estado')
        ->where('tbd_denuncia.tbd_empresa_id', '=', $company->tbd_empresa_id)
        ->orderBy('tbd_denuncia.id', 'desc')
        ->get();
      } else {
        $data = Complaint::select(
          "tbd_denuncia.id",
          "tbd_denuncia.code",
          "tbd_denuncia.fecha AS created_at",
          "tbd_denuncia.fecha_cierre AS closed_at",
          "tbd_denuncia.descrip AS description",
          "tbd_denuncia.nombre AS name",
          "tbd_denuncia.dni AS dni",
          "tbd_denuncia.telf AS cellphone",
          "tbd_denuncia.correo AS email",
          "tbd_denuncia.terms",
          "tbd_denuncia.sustento AS file",
          "tbd_causa.descrip AS reason",
          "tbd_denuncia.tbd_causa_id AS reasonId",
          "tbd_relacion.descrip AS relation",
          "tbd_denuncia.tbd_relacion_id AS relationId",
          "tbd_denuncia.tbd_empresa_id AS companyId",
          "tbd_denuncia.tbd_empresauser_id AS companyuserId",
          "tbd_estado.code AS state",
          "tbd_estado.name AS state_name"
        )
        ->join('tbd_causa', 'tbd_causa.id', '=', 'tbd_denuncia.tbd_causa_id')
        ->join('tbd_relacion', 'tbd_relacion.id', '=', 'tbd_denuncia.tbd_relacion_id')
        ->leftjoin('tbd_estado', 'tbd_estado.code', '=', 'tbd_denuncia.estado')
        // ->join('tbd_empresauser', 'tbd_empresauser.tbd_empresa_id', '=', 'tbd_denuncia.tbd_empresauser_id')
        // ->where('tbd_empresauser.wp_users_id', '=', $userId)
        ->where('tbd_denuncia.tbd_empresauser_id', '=', $userId)
        ->orderBy('tbd_denuncia.id', 'desc')
        ->get();
      }
    }

    return datatables()->of($data)
      ->addIndexColumn()
      ->addColumn('status', function ($item) {
        return array(
          "code" => $item->state,
          "name" => $item->state_name,
          "closed_at" => $item->closed_at,
          "created_at" => $item->created_at,
        );
      })
      ->addColumn('actions', function ($item) {
        return array(
          "id" => $item->id,
          "status" => $item->state,
        );
      })
      ->rawColumns(['status', 'actions'])
      ->make(true);
  }

  public function details($id) {
    $data = Complaint::select(
      "tbd_denuncia.id",
      "tbd_denuncia.code",
      "tbd_denuncia.fecha AS created_at",
      "tbd_denuncia.fecha_cierre AS closed_at",
      "tbd_denuncia.descrip AS description",
      "tbd_denuncia.nombre AS name",
      "tbd_denuncia.dni AS dni",
      "tbd_denuncia.telf AS cellphone",
      "tbd_denuncia.correo AS email",
      "tbd_denuncia.terms",
      "tbd_denuncia.sustento AS file",
      "tbd_causa.descrip AS reason",
      "tbd_denuncia.tbd_causa_id AS reasonId",
      "tbd_relacion.descrip AS relation",
      "tbd_denuncia.tbd_relacion_id AS relationId",
      "tbd_denuncia.tbd_empresa_id AS companyId",
      "tbd_denuncia.tbd_empresauser_id AS companyuserId",
      "tbd_denuncia.estado as status",
      "tbd_estado.code AS state",
      "tbd_estado.name AS state_name"
    )
    ->join('tbd_causa', 'tbd_causa.id', '=', 'tbd_denuncia.tbd_causa_id')
    ->join('tbd_relacion', 'tbd_relacion.id', '=', 'tbd_denuncia.tbd_relacion_id')
    ->leftjoin('tbd_estado', 'tbd_estado.code', '=', 'tbd_denuncia.estado')
    // ->join('tbd_empresauser', 'tbd_empresauser.tbd_empresa_id', '=', 'tbd_denuncia.tbd_empresauser_id')
    // ->where('tbd_empresauser.wp_users_id', '=', $request->userId)
    ->where('tbd_denuncia.id', '=', $id)
    ->first();

    $relations = ComplaintRelation::select(
        'tbd_persona.id',
        'tbd_persona.tipo as type',
        'tbd_persona.nombre as name',
        'tbd_persona.dni as identification',
        'tbd_persona.codigo as code',
        'tbd_rol.descrip as rol'
      )
      ->leftjoin('tbd_rol', 'tbd_rol.id', '=', 'tbd_persona.tbd_rol_id')
      ->where('tbd_persona.tbd_denuncia_id', '=', $id)
      ->get();
    $files = ComplaintFile::select('id', 'base', 'url')->where('tbd_denuncia_id', '=', $id)->get();
    $historial = ComplaintHistorial::select(
        'tbd_historial.id',
        'tbd_historial.label',
        'tbd_historial.message',
        'tbd_historial.wp_users_id as userId',
        'wp_users.display_name as fullname',
        'tbd_historial.tbd_denuncia_id',
        'tbd_historial.createdAt'
      )
      ->leftjoin('wp_users', 'wp_users.ID', '=', 'tbd_historial.wp_users_id')
      ->where('tbd_historial.tbd_denuncia_id', '=', $id)
      ->orderBy('tbd_historial.id', 'asc')
      ->get();

    $data->relations = $relations;
    $data->files = $files;
    $data->historial = $historial;

    return $data;
  }

  public function detailsPDF($iduser, $id) {
    if (!isset($iduser) && !isset($id)) {
      return false;
    }

    $user = User::where("ID", "=", $iduser)->first();

    $data = Complaint::select(
      "tbd_denuncia.id",
      "tbd_denuncia.code",
      "tbd_denuncia.fecha AS created_at",
      "tbd_denuncia.fecha_cierre AS closed_at",
      "tbd_denuncia.descrip AS description",
      "tbd_denuncia.nombre AS name",
      "tbd_denuncia.dni AS dni",
      "tbd_denuncia.telf AS cellphone",
      "tbd_denuncia.correo AS email",
      "tbd_denuncia.terms",
      "tbd_denuncia.sustento AS file",
      "tbd_causa.descrip AS reason",
      "tbd_denuncia.tbd_causa_id AS reasonId",
      "tbd_relacion.descrip AS relation",
      "tbd_denuncia.tbd_relacion_id AS relationId",
      "tbd_denuncia.tbd_empresa_id AS companyId",
      "tbd_denuncia.tbd_empresauser_id AS companyuserId",
      "tbd_denuncia.estado as status",
      "tbd_estado.code AS state",
      "tbd_estado.name AS state_name"
    )
    ->join('tbd_causa', 'tbd_causa.id', '=', 'tbd_denuncia.tbd_causa_id')
    ->join('tbd_relacion', 'tbd_relacion.id', '=', 'tbd_denuncia.tbd_relacion_id')
    ->leftjoin('tbd_estado', 'tbd_estado.code', '=', 'tbd_denuncia.estado')
    // ->join('tbd_empresauser', 'tbd_empresauser.tbd_empresa_id', '=', 'tbd_denuncia.tbd_empresauser_id')
    // ->where('tbd_empresauser.wp_users_id', '=', $request->userId)
    ->where('tbd_denuncia.id', '=', $id)
    ->first();

    $relations = ComplaintRelation::select(
        'tbd_persona.id',
        'tbd_persona.tipo as type',
        'tbd_persona.nombre as name',
        'tbd_persona.dni as identification',
        'tbd_persona.codigo as code',
        'tbd_rol.descrip as rol'
      )
      ->leftjoin('tbd_rol', 'tbd_rol.id', '=', 'tbd_persona.tbd_rol_id')
      ->where('tbd_persona.tbd_denuncia_id', '=', $id)
      ->get();
    $files = ComplaintFile::select('id', 'base', 'url')->where('tbd_denuncia_id', '=', $id)->get();
    $historial = ComplaintHistorial::select(
        'tbd_historial.id',
        'tbd_historial.label',
        'tbd_historial.message',
        'tbd_historial.wp_users_id as userId',
        'wp_users.display_name as fullname',
        'tbd_historial.tbd_denuncia_id',
        'tbd_historial.createdAt'
      )
      ->leftjoin('wp_users', 'wp_users.ID', '=', 'tbd_historial.wp_users_id')
      ->where('tbd_historial.tbd_denuncia_id', '=', $id)
      ->orderBy('tbd_historial.id', 'asc')
      ->get();

    $data->relations = $relations;
    $data->files = $files;
    $data->historial = $historial;

    $path = 'pdfs.complaintdetails';
    $result = array(
      "meta" => [
        "fullname" => isset($user) ? $user->display_name . " " . $user->lastname : '',
        "created_at" => date("d-m-Y h:i:s A"),
      ],
      "data" => $data,
    );
    view()->share($path, $result);

    $pdf = PDF::loadView($path, ["result" => $result]);

    return $pdf->download('Detalle-de-Canal-de-Denuncia.pdf');
  }

  public function createMessage(Request $request) {
    $user = User::where('ID', '=', $request->userId)->first();

    $historial = new ComplaintHistorial();
    $historial->message = $request->message;
    $historial->wp_users_id = $request->userId;
    $historial->tbd_denuncia_id = $request->complaintId;
    $historial->createdAt = date("Y-m-d h:i:s");

    $historial->save();

    $historial->fullname = $user->display_name;

    Complaint::where('id', '=', $request->complaintId)->update([ "estado" => 2 ]);

    return $historial;
  }

  public function setExpirationDate(Request $request) {
    $complaintId = $request->id;
    $expirationDate = $request->expirationDate;

    $struct = [
      "fecha_cierre" => $expirationDate,
    ];

    Complaint::where('id', '=', $complaintId)->update($struct);
    $complaint = Complaint::find($complaintId);

    return $complaint;
  }

  public function close(Request $request) {
    $complaintId = $request->id;
    $file = $request->file;

    $store = "public/complaints/$complaintId";
    $filename = time() . '.' . $file->extension();

    Storage::disk('local')->putFileAs($store, $file, $filename);

    $struct = [
      "sustento" => "/storage/complaints/$complaintId/$filename",
      "estado" => 4,
    ];

    Complaint::where('id', '=', $complaintId)->update($struct);
    $complaint = Complaint::find($complaintId);

    return $complaint;
  }

  public function closeIncomplete(Request $request) {
    $complaintId = $request->id;
    $ownerId = $request->ownerId;

    $struct = [
      "estado" => 3,
    ];

    Complaint::where('id', '=', $complaintId)->update($struct);
    $complaint = Complaint::find($complaintId);

    /* Begin - Historial */
    $user = User::find($request->ownerId);
    $username = $user->display_name . " " . $user->lastname;
    $label = "<span style='font-weight: bold;'>{$username}</span> cerr&oacute; la denuncia con motivo incompleto por falta de respuesta.</span>";

    $historial = new ComplaintHistorial();
    $historial->label = $label;
    $historial->wp_users_id = $ownerId;
    $historial->tbd_denuncia_id = $complaintId;
    $historial->createdAt = date("Y-m-d h:i:s");
    $historial->save();
    /* End - Historial */

    $historial->fullname = $username;
    $complaint->historial = $historial;

    return $complaint;
  }

  public function team($iduser, $complaintid) {
    $company = ComplaintCompany::select("tbd_empresa_id")->where("wp_users_id", '=', $iduser)->first();
    $complaint = Complaint::select("tbd_empresauser_id")->where("id", '=', $complaintid)->first();

    $items = ComplaintCompany::select(
        "tbd_empresauser.id",
        "tbd_empresauser.tbd_empresa_id as companyId",
        "tbd_empresauser.wp_users_id as userId",
        DB::raw("CONCAT(wp_users.display_name,' ',IFNULL(wp_users.lastname,'')) as fullname"),
      )
      ->join('wp_users', 'wp_users.ID', '=', 'tbd_empresauser.wp_users_id')
      ->where('tbd_empresauser.tbd_empresa_id', '=', $company->tbd_empresa_id)
      ->get();

    $data = [];
    foreach ($items as $item) {
      if ((int)$item->userId !== (int)$complaint->tbd_empresauser_id) {
        $data[] = $item;
      }
    }

    return $data;
  }

  public function assign(Request $request) {
    $struct = [
      "tbd_empresauser_id" => $request->userId,
      "estado" => 1,
    ];
    Complaint::where('id', '=', $request->complaintId)->update($struct);
    $complaint = Complaint::find($request->complaintId);

    /* Begin - Historial */
    $user = User::find($request->ownerId);
    $username = $user->display_name . " " . $user->lastname;
    $userAssigned = User::find($request->userId);
    $usernameAssigned = $userAssigned->display_name . " " . $userAssigned->lastname;
    $label = "<span style='font-weight: bold;'>{$username}</span> asign&oacute; la denuncia a <span style='font-weight: bold;'>{$usernameAssigned}</span>";

    $historial = new ComplaintHistorial();
    $historial->label = $label;
    $historial->wp_users_id = $request->userId;
    $historial->tbd_denuncia_id = $request->complaintId;
    $historial->createdAt = date("Y-m-d h:i:s");
    $historial->save();
    /* End - Historial */

    return $complaint;
  }
}
