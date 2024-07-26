<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Risk;
use App\Models\BusinessMeta;
use App\Models\Area;
use App\Models\Process;
use App\Models\UserArea;
use App\Models\CompanyUser;
use App\Models\CompanySize;
use App\Models\AreaProcess;

use Session;
use DB;
use PDF;

class RiskController extends Controller {
  public function viewList() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }
    $userId = Session::get("loginId");

    return view("pages.risks.index", compact("userId"));
  }

  public function viewGenerate() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }
    $userId = Session::get("loginId");
    $sizes = CompanySize::select("tamanodescrip AS name")->orderBy('tamanodescrip', 'asc')->get();
    $companyType = $this->detectCompanyTypeGenerate($userId, $sizes);

    $areas = $this->listAreasByCompany($userId);

    $processes = Process::select(
      "proceso_id as id",
      "nombre as name"
    )
      ->orderBy("nombre", "asc")
      ->get();

    return view("pages.risks.generate.index", compact('userId', 'companyType', "sizes", "areas", "processes"));
  }

  public function detectCompanyTypeGenerate($userId, $dbSizes) {
    $meta = BusinessMeta::where([
      [ "user_id", "=", $userId ],
      [ "meta_key", "=", "tipo_empresa" ],
    ])->first();
    $companyType = isset($meta) ? $meta->meta_value : '';

    if ($companyType == '') return '';

    $nameSizes = [];
    foreach ($dbSizes as $size) {
      $nameSizes[] = $size->name;
    }

    return in_array($companyType, $nameSizes) ? $companyType : "Pequeña Empresa";
  }

  public function detectCompanyTypeDetails($companyType, $dbSizes) {
    if (!$companyType) {
      return "";
    }
    $nameSizes = [];
    foreach ($dbSizes as $size) {
      $nameSizes[] = $size->name;
    }
    return in_array($companyType, $nameSizes) ? $companyType : "Pequeña Empresa";
  }

  public function viewDetails($id) {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }
    $userId = Session::get("loginId");
    $sizes = CompanySize::select("tamanodescrip AS name")->orderBy('tamanodescrip', 'asc')->get();

    $risk = Risk::select(
        "riesgo_id                    as    id",
        "usuario_id                   as    userId",
        "tipo_empresa_id              as    companyTypeId",
        "tipo_empresa_nombre          as    companyTypeName",
        "titulo",                     // as    title", <-- encode from model
        "area_empresa_id              as    companyAreaId",
        "area_empresa_nombre          as    companyAreaName",
        "proceso_empresa_id           as    companyProcessId",
        "proceso_empresa_nombre       as    companyProcessName",
        "detalle_riesgo               as    details",
        "factor_id                    as    factorId",
        "factor_nombre                as    factorName",
        "probabilidad_id              as    probId",
        "probabilidad_nombre          as    probName",
        "probabilidad_inherente       as    probInher",
        "probabilidad_inherente_id    as    probInherId",
        "impacto_estimado             as    impEstim",
        "impacto_inherente            as    impInher",
        "impacto_inherente_id         as    impInherId",
        "descripcion_control          as    ctrlDescription",
        "documento_fuente",           // as    ctrlDocument", <-- encode from model
        "area_ejecucion_id            as    ctrlAreaId",
        "area_ejecucion_nombre        as    ctrlAreaName",
        "periodicidad_valor           as    ctrlPeriodId",
        "periodicidad_nombre          as    ctrlPeriodName",
        "operatividad_valor           as    ctrlOperId",
        "operatividad_nombre          as    ctrlOperName",
        "tipo_control_valor           as    ctrlTypeId",
        "tipo_control_nombre          as    ctrlTypeName",
        "supervision_valor            as    ctrlSuperId",
        "supervision                  as    ctrlSuperName",
        "frecuencia_control_valor     as    ctrlFreqId",
        "frecuencia_control_nombre    as    ctrlFreqName",
        "seguimiento_adecuado_valor   as    ctrlFollId",
        "seguimiento_adecuado_nombre  as    ctrlFollName",
        "probabilidad_residual        as    probRes",
        "probabilidad_residual_id     as    probResId",
        "impacto_residual             as    impRes",
        "impacto_residual_id          as    impResId",
        "plan_accion",                // as    planDescr", <-- encode from model
        "area_responsable_id          as    planAreaId",
        "area_responsable_nombre      as    planAreaName",
        "archivo_adjunto              as    file",
        "fecha_creacion               as    fecCreated",
        "fecha_inicio                 as    fecStart",
        "fecha_fin                    as    fecEnd",
        "estado                       as    status",
        "origin                       as    origin"
      )
      ->where('riesgo_id', '=', $id)
      ->first();

    $risk->status = (int)$risk->status;
    $risk->fecStart = date('Y-m-d', strtotime(str_replace('-','/', $risk->fecStart)));
    $risk->fecEnd = date('Y-m-d', strtotime(str_replace('-','/', $risk->fecEnd)));

    $splitted = explode("/", $risk->file);
    $filename = end($splitted);

    $companyType = $this->detectCompanyTypeDetails($risk->companyTypeName, $sizes);

    return view("pages.risks.details.index", compact('userId', 'risk', 'filename', 'companyType', 'sizes'));
  }

  public function listAreasByCompany($userId) {
    $user = CompanyUser::where("wp_users_id", "=", $userId)->first();
    $users = CompanyUser::where("tbd_empresa_id", "=", $user->tbd_empresa_id)->get();
    $userIds = [];
    foreach ($users as $usr) {
      $userIds[] = $usr->wp_users_id;
    }

    $allareas = UserArea::select(
      "tb_division_area.division_area_id as id",
      "tb_division_area.nombre as name"
    )
      ->join("tb_division_area", "tb_division_area.division_area_id", "=", "tb_usuario_area.area_id")
      ->whereIn("tb_usuario_area.usuario_id", $userIds)
      ->orderBy("tb_division_area.nombre", "asc")
      ->get();
    $areas = [];
    foreach ($allareas as $ar) { // [{ id: 1, name: ale }, { id: 2, name: hen }]
      if (!in_array($ar, $areas)) {
        $areas[] = $ar;
      }
    }
    return $areas;
  }

  public function list($type, $userId) {
    $risks = [];

    if ($type === 'inher') {
      $risks = Risk::selectRaw('COUNT(*) AS y, CONCAT(probabilidad_inherente_id, impacto_inherente_id) AS label')
        ->where('usuario_id', '=', $userId)
        ->orWhereJsonContains('userIds', [$userId])
        ->groupBy('probabilidad_inherente_id', 'impacto_inherente_id')
        ->get();
    }

    if ($type === 'res') {
      $risks = Risk::selectRaw('COUNT(*) AS y, CONCAT(probabilidad_residual_id, impacto_residual_id) AS label')
        ->where('usuario_id', '=', $userId)
        ->orWhereJsonContains('userIds', [$userId])
        ->groupBy('probabilidad_residual_id', 'impacto_residual_id')
        ->get();
    }

    return $risks;
  }

  function setColor($x, $y) {
    $colors = array(
      "11" => "#FFFD0A", "12" => "#FFFD0A", "13" => "#FF8A00", "14" => "#FF0000", "15" => "#FF0000",
      "21" => "#429A46", "22" => "#FFFD0A", "23" => "#FFFD0A", "24" => "#FF8A00", "25" => "#FF0000",
      "31" => "#429A46", "32" => "#429A46", "33" => "#FFFD0A", "34" => "#FFFD0A", "35" => "#FF8A00",
      "41" => "#469BE7", "42" => "#429A46", "43" => "#429A46", "44" => "#FFFD0A", "45" => "#FFFD0A",
      "51" => "#469BE7", "52" => "#469BE7", "53" => "#429A46", "54" => "#429A46", "55" => "#FFFD0A",
    );
    if (isset($x) && isset($y)) {
      $coord = $y.$x;
      return $colors[$coord];
    }
    return '';
  }

  function setState($status) {
    $state = array(
      "1" => "<span>Editando</span>",
      "2" => "<span>Abierto</span>",
      "3" => "<span>Cerrado</span>",
      "4" => "<span style='color: red'>Vencido</span>",
    );
    return isset($status) ? $state[$status] : '';
  }

  public function dtTable(Request $request) {
    /*$query = "SELECT
      riesgo_id,
      titulo,
      probabilidad_id,
      probabilidad_nombre,
      probabilidad_inherente,
      probabilidad_inherente_id,
      impacto_estimado,
      impacto_inherente,
      impacto_inherente_id,
      CONCAT(probabilidad_inherente_id, impacto_inherente_id) AS coordenada1,
      probabilidad_residual,
      probabilidad_residual_id,
      impacto_residual,
      impacto_residual_id,
      CONCAT(probabilidad_residual_id, impacto_residual_id) AS coordenada2,
      fecha_creacion,
      fecha_inicio,
      fecha_fin,
      estado
    FROM tb_riesgo
    WHERE usuario_id = 3
    ORDER BY riesgo_id;*/
    $data = Risk::select(
        "riesgo_id AS id",
        "titulo", // AS title", <-- encode from model
        "probabilidad_id AS idProb",
        "probabilidad_nombre AS probName",
        "probabilidad_inherente AS probInher",
        "probabilidad_inherente_id AS probInherId",
        "impacto_estimado AS impEstim",
        "impacto_inherente AS impInher",
        "impacto_inherente_id AS impInherId",
        // DB::raw("CONCAT(probabilidad_inherente_id, impacto_inherente_id) AS coord1"),
        "probabilidad_residual AS probRes",
        "probabilidad_residual_id AS probResId",
        "impacto_residual AS impRes",
        "impacto_residual_id AS impResId",
        // DB::raw("CONCAT(probabilidad_residual_id, impacto_residual_id) AS coord2"),
        "fecha_creacion AS created_at",
        "fecha_inicio AS started_at",
        "fecha_fin AS finished_at",
        "estado AS state"
      )
      ->where('usuario_id', '=', $request->userId)
      ->orWhereJsonContains('userIds', [$request->userId])
      ->orderBy('riesgo_id', 'desc')
      ->get();
    return datatables()->of($data)
      ->addIndexColumn()
      ->addColumn('title', function ($item) {
        return Risk::decode($item->titulo);
        // return $item->titulo;
      })
      ->addColumn('adn', function ($item) {
        $btn = "<div class='flex justify-center'><input id='{$item->id}' type='checkbox' class='checkbox' /></div>";
        return $btn;
      })
      ->addColumn('colorCoord1', function ($item) {
        $color = $this->setColor($item->impInherId, $item->probInherId);
        $cell = "<div style='background-color: $color; height: 40px'></div>";
        return $cell;
      })
      ->addColumn('colorCoord2', function ($item) {
        $color = $this->setColor($item->impResId, $item->probResId);
        $cell = "<div style='background-color: $color; height: 40px'></div>";
        return $cell;
      })
      ->addColumn('status', function ($item) {
        return $this->setState($item->state);
      })
      ->addColumn('actions', function ($item) {
        return array(
          "id" => $item->id,
          "status" => $item->state,
        );
      })
      ->rawColumns(['adn', 'colorCoord1', 'colorCoord2', 'status', 'action'])
      ->make(true);
  }

  public function create(Request $request) {
    $isValid = TRUE;
    $keyEmpty = "";
    foreach ($request->keys() as $field) {
      if ($request[$field] !== 0 && $request[$field] !== "0") {
        if (empty($request[$field])) {
          $isValid = FALSE;
          $keyEmpty = $field;
          break;
        }
      }
    }

    if ($request->type === 'register') {
      if (!$isValid) {
        return response()->json([
          'message' => __("validation.custom.risks-creation-failed.empty") . ": "  . $keyEmpty,
        ], 500);
      }
    }

    $meta = BusinessMeta::where([
      [ "user_id", "=", $request->userId ],
      [ "meta_key", "=", "tipo_empresa" ],
    ])->first();
    $companyType = isset($meta) ? $meta->meta_value : '';

    $risk = new Risk();
    $risk->usuario_id                   = $request->userId;
    $risk->tipo_empresa_id              = $request->companyTypeId;
    $risk->tipo_empresa_nombre          = $request->companyTypeName;
    $risk->titulo                       = $request->title;
    $risk->area_empresa_id              = $request->companyAreaId;
    $risk->area_empresa_nombre          = $request->companyAreaName;
    $risk->proceso_empresa_id           = $request->companyProcessId;
    $risk->proceso_empresa_nombre       = $request->companyProcessName;
    $risk->detalle_riesgo               = $request->details;
    $risk->factor_id                    = $request->factorId;
    $risk->factor_nombre                = $request->factorName;
    $risk->probabilidad_id              = $request->probId;
    $risk->probabilidad_nombre          = $request->probName;
    $risk->probabilidad_inherente       = $request->probInher;
    $risk->probabilidad_inherente_id    = $request->probInherId;
    $risk->impacto_estimado             = $request->impEstim;
    $risk->impacto_inherente            = $request->impInher;
    $risk->impacto_inherente_id         = $request->impInherId;
    $risk->descripcion_control          = $request->ctrlDescription;
    $risk->documento_fuente             = $request->ctrlDocument;
    $risk->area_ejecucion_id            = $request->ctrlAreaId;
    $risk->area_ejecucion_nombre        = $request->ctrlAreaName;
    $risk->periodicidad_valor           = $request->ctrlPeriodId;
    $risk->periodicidad_nombre          = $request->ctrlPeriodName;
    $risk->operatividad_valor           = $request->ctrlOperId;
    $risk->operatividad_nombre          = $request->ctrlOperName;
    $risk->tipo_control_valor           = $request->ctrlTypeId;
    $risk->tipo_control_nombre          = $request->ctrlTypeName;
    $risk->supervision_valor            = $request->ctrlSuperId;
    $risk->supervision                  = $request->ctrlSuperName;
    $risk->frecuencia_control_valor     = $request->ctrlFreqId;
    $risk->frecuencia_control_nombre    = $request->ctrlFreqName;
    $risk->seguimiento_adecuado_valor   = $request->ctrlFollId;
    $risk->seguimiento_adecuado_nombre  = $request->ctrlFollName;
    $risk->probabilidad_residual        = $request->probRes;
    $risk->probabilidad_residual_id     = $request->probResId;
    $risk->impacto_residual             = $request->impRes;
    $risk->impacto_residual_id          = $request->impResId;
    $risk->plan_accion                  = $request->planDescr;
    $risk->area_responsable_id          = $request->planAreaId;
    $risk->area_responsable_nombre      = $request->planAreaName;
    $risk->fecha_creacion               = date("Y-m-d h:i:s");
    $risk->fecha_inicio                 = $request->fecStart;
    $risk->fecha_fin                    = $request->fecEnd;
    $risk->estado                       = $request->status;
    $risk->origin                       = "client-conoce";

    $risk->save();

    return $risk;
  }

  public function detailsPDF($iduser, $id) {
    if (!isset($iduser) && !isset($id)) {
      return false;
    }

    $user = User::where("ID", "=", $iduser)->first();

    $risk = Risk::select(
        "riesgo_id                    as    id",
        "usuario_id                   as    userId",
        "tipo_empresa_id              as    companyTypeId",
        "tipo_empresa_nombre          as    companyTypeName",
        "titulo",                     // as    title", <-- encode from model
        "area_empresa_id              as    companyAreaId",
        "area_empresa_nombre          as    companyAreaName",
        "proceso_empresa_id           as    companyProcessId",
        "proceso_empresa_nombre       as    companyProcessName",
        "detalle_riesgo               as    details",
        "factor_id                    as    factorId",
        "factor_nombre                as    factorName",
        "probabilidad_id              as    probId",
        "probabilidad_nombre          as    probName",
        "probabilidad_inherente       as    probInher",
        "probabilidad_inherente_id    as    probInherId",
        "impacto_estimado             as    impEstim",
        "impacto_inherente            as    impInher",
        "impacto_inherente_id         as    impInherId",
        "descripcion_control          as    ctrlDescription",
        "documento_fuente",           // as    ctrlDocument",
        "area_ejecucion_id            as    ctrlAreaId",
        "area_ejecucion_nombre        as    ctrlAreaName",
        "periodicidad_valor           as    ctrlPeriodId",
        "periodicidad_nombre          as    ctrlPeriodName",
        "operatividad_valor           as    ctrlOperId",
        "operatividad_nombre          as    ctrlOperName",
        "tipo_control_valor           as    ctrlTypeId",
        "tipo_control_nombre          as    ctrlTypeName",
        "supervision                  as    ctrlSuperName",
        "frecuencia_control_valor     as    ctrlFreqId",
        "frecuencia_control_nombre    as    ctrlFreqName",
        "seguimiento_adecuado_valor   as    ctrlFollId",
        "seguimiento_adecuado_nombre  as    ctrlFollName",
        "probabilidad_residual        as    probRes",
        "probabilidad_residual_id     as    probResId",
        "impacto_residual             as    impRes",
        "impacto_residual_id          as    impResId",
        "plan_accion",                // as    planDescr",
        "area_responsable_id          as    planAreaId",
        "area_responsable_nombre      as    planAreaName",
        "archivo_adjunto              as    file",
        "fecha_creacion               as    fecCreated",
        "fecha_inicio                 as    fecStart",
        "fecha_fin                    as    fecEnd",
        "estado                       as    status"
      )
      ->where('riesgo_id', '=', $id)
      ->first();

    $xy = "{$risk->probInherId}{$risk->impInherId}";
    $xy_ = "{$risk->probResId}{$risk->impResId}";
    $risk->xy = $xy;
    $risk->xy_ = $xy_;

    $path = 'pdfs.riskdetails';
    $result = array(
      "meta" => [
        "fullname" => isset($user) ? $user->display_name . " " . $user->lastname : '',
        "created_at" => date("d-m-Y h:i:s A"),
      ],
      "data" => $risk,
    );
    view()->share($path, $result);

    $pdf = PDF::loadView($path, ["result" => $result]);

    return $pdf->download('Detalle-de-Matriz-de-Riesgo.pdf');
  }

  public function modify(Request $request) {
    $isValid = TRUE;
    $keyEmpty = "";
    foreach ($request->keys() as $field) {
      if ($request[$field] !== 0 && $request[$field] !== "0") {
        if (empty($request[$field])) {
          $isValid = FALSE;
          $keyEmpty = $field;
          break;
        }
      }
    }

    if ($request->type === 'register') {
      if (!$isValid) {
        return response()->json([
          'message' => __("validation.custom.risks-creation-failed.empty") . ": "  . $keyEmpty,
        ], 500);
      }
    }

    $risk = Risk::find($request->idRisk); // Risk::where("riesgo_id", "=", $request->idRisk)->first();
    $risk->tipo_empresa_id              = $request->companyTypeId;
    $risk->tipo_empresa_nombre          = $request->companyTypeName;
    $risk->titulo                       = $request->title;
    $risk->area_empresa_id              = $request->companyAreaId;
    $risk->area_empresa_nombre          = $request->companyAreaName;
    $risk->proceso_empresa_id           = $request->companyProcessId;
    $risk->proceso_empresa_nombre       = $request->companyProcessName;
    $risk->detalle_riesgo               = $request->details;
    $risk->factor_id                    = $request->factorId;
    $risk->factor_nombre                = $request->factorName;
    $risk->probabilidad_id              = $request->probId;
    $risk->probabilidad_nombre          = $request->probName;
    $risk->probabilidad_inherente       = $request->probInher;
    $risk->probabilidad_inherente_id    = $request->probInherId;
    $risk->impacto_estimado             = $request->impEstim;
    $risk->impacto_inherente            = $request->impInher;
    $risk->impacto_inherente_id         = $request->impInherId;
    $risk->descripcion_control          = $request->ctrlDescription;
    $risk->documento_fuente             = $request->ctrlDocument;
    $risk->area_ejecucion_id            = $request->ctrlAreaId;
    $risk->area_ejecucion_nombre        = $request->ctrlAreaName;
    $risk->periodicidad_valor           = $request->ctrlPeriodId;
    $risk->periodicidad_nombre          = $request->ctrlPeriodName;
    $risk->operatividad_valor           = $request->ctrlOperId;
    $risk->operatividad_nombre          = $request->ctrlOperName;
    $risk->tipo_control_valor           = $request->ctrlTypeId;
    $risk->tipo_control_nombre          = $request->ctrlTypeName;
    $risk->supervision_valor            = $request->ctrlSuperId;
    $risk->supervision                  = $request->ctrlSuperName;
    $risk->frecuencia_control_valor     = $request->ctrlFreqId;
    $risk->frecuencia_control_nombre    = $request->ctrlFreqName;
    $risk->seguimiento_adecuado_valor   = $request->ctrlFollId;
    $risk->seguimiento_adecuado_nombre  = $request->ctrlFollName;
    $risk->probabilidad_residual        = $request->probRes;
    $risk->probabilidad_residual_id     = $request->probResId;
    $risk->impacto_residual             = $request->impRes;
    $risk->impacto_residual_id          = $request->impResId;
    $risk->plan_accion                  = $request->planDescr;
    $risk->area_responsable_id          = $request->planAreaId;
    $risk->area_responsable_nombre      = $request->planAreaName;
    // $risk->fecha_creacion               = date("Y-m-d h:i:s");
    $risk->fecha_inicio                 = $request->fecStart;
    $risk->fecha_fin                    = $request->fecEnd;
    $risk->estado                       = $request->status;

    $risk->save();

    return $risk;
  }

  public function uploadFile(Request $request) {
    // Input
    $idRisk = $request->idRisk;
    $file = $request->file;

    if (!isset($file)) {
      return response()->json([
        'message' => __("validation.custom.risks-creation-failed.required__file"),
      ], 500);
    }

    // Data
    $store = 'public/risks/' . $idRisk;
    $filename = time() . '.' . $file->extension();

    // Process
    Storage::disk('local')->putFileAs($store, $file, $filename);

    $struct = [
      'archivo_adjunto' => "/storage/risks/$idRisk/$filename",
    ];

    Risk::where('riesgo_id', '=', $idRisk)->update($struct);
    $risk = Risk::find($idRisk);

    // Response
    return $risk;
  }

  private function str2lst($str) {
    $aux = str_replace("[", "", $str);
    $aux = str_replace("]", "", $aux);
    $aux = str_replace('"', '', $aux);
    $aux = str_replace("'", "", $aux);
    $arr = [];
    $splitted = explode(",", $aux);
    foreach ($splitted as $item) {
      $jj = trim($item);
      if (!empty($jj)) {
        $arr[] = $jj;
      }
    }
    return $arr;
  }

  public function assign(Request $request) {
    $userId = $request->userId;
    $riskId = $request->riskId;

    $risk = Risk::find($riskId);
    $risk->userIds = $this->str2lst($risk->userIds);

    $userIds = $risk->userIds;
    if (in_array($userId, $userIds)) {
      return $risk;
    }
    $userIds[] = strval($userId);
    $risk->userIds = $userIds;
    $risk->save();

    return $risk;
  }

  public function createProcess(Request $request) {
    $process = new Process();
    $process->nombre = $request->process;
    $process->fecha_creacion = now();
    $process->fecha_modificacion = now();
    $process->estado = 1;
    $process->save();
    return $process;
  }

  public function createArea(Request $request) {
    $area = new Area();
    $area->nombre = $request->area;
    $area->fecha_creacion = now();
    $area->fecha_modificacion = now();
    $area->estado = 1;
    $area->save();

    $userarea = new UserArea();
    $userarea->area_id = $area->division_area_id;
    $userarea->usuario_id = $request->userId;
    $userarea->save();
    return $area;
  }

  public function deleteProcess($id) {
    Process::where("proceso_id", "=", $id)->delete();
    return true;
  }

  public function deleteArea($id) {
    Area::where("division_area_id", "=", $id)->delete();
    AreaProcess::where("area_id", "=", $id)->delete();
    return true;
  }

  public function listProcessesByArea($areaId) {
    $processes = AreaProcess::select(
      "tb_proceso.proceso_id as id",
      "tb_proceso.nombre as name"
    )
      ->join("tb_proceso", "tb_proceso.proceso_id", "=", "tb_area_proceso.proceso_id")
      ->where("tb_area_proceso.area_id", "=", $areaId)
      ->orderBy("tb_proceso.nombre", "asc")
      ->get();

    $processIds = [];
    foreach ($processes as $process) {
      $processIds[] = $process->id;
    }

    return $processIds;
  }

  public function createAreaProcesses(Request $request) {
    $areaId = $request->areaId;
    $processIds = $request->processIds;

    $data = [];
    foreach ($processIds as $processId) {
      $data[] = [
        "area_id" => $areaId,
        "proceso_id" => $processId,
      ];
    }

    AreaProcess::where("area_id", "=", $areaId)->delete();

    if (count($data) > 0) {
      AreaProcess::insert($data);
    }

    return $data;
  }
}
