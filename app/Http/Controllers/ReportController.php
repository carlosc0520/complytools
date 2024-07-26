<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use App\Models\OperationPeople;

use Illuminate\Http\Request;

use DB;
use Session;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller {
  public function viewList() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $userId = Session::get("loginId");

    return view("pages.reports.index", compact('userId'));
  }

  public function viewGenerate() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }
    $userId = Session::get("loginId");

    return view("pages.reports.generate.index", compact('userId'));
  }

  public function viewDetails($id) {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }
    $userId = Session::get("loginId");
    $reportId = $id;

    $report = Report::select(
        "userid           as userId",
        "fechacreacion    as createdAt",
        "estado           as status",
  
        "optipo           as typeId",
        "opurgencia       as urgencyId",
        "opoficina        as office",
        "opdireccion      as address",
        "opdepartamento   as departmentId",
        "opprovincia      as provinceId",
        "opdistrito       as districtId",
        "optsenal         as signaltypeId",
        "opsenales        as signalIds",
        "opdelito         as crimeId",
        "delitosoperacion as crimetypeIds",
        "opactividad      as activityId",
        "opdetalle        as details",
        "opproducto       as product",
        "opmonto          as amount",
        "opmoneda         as currencyId",
        "opfechadesde     as startedAt",
        "opfechahasta     as finishedAt",
        "opadicional      as extra"
      )
      ->where('operacionid', '=', $id)
      ->first();
    $people = OperationPeople::select(
        "peropid            as id",
        "operacionid        as reportId",
        "personatiponj      as henry",
        "personatipo        as peopletype",
        "personacond        as condition",
        "personarazon       as company",
        "personaruc         as ruc",
        "personaapellidopat as lastname1",
        "personaapellidomat as lastname2",
        "personanombres     as name",
        "personafechanac    as birthday",
        "personanac         as nationality",
        "personapep         as pep",
        "personatipodoc     as documenttype",
        "personadocumento   as documentnumber",
        "personaprof        as ocupation",
        "personatelef       as cellphone",
        "personaemail       as email",
        "personadomicilio   as address",
        "personapais        as country",
        "personadep         as department",
        "personaprov        as province",
        "personadist        as district",
      )
      ->where('operacionid', '=', $id)
      ->get();

    $report->status = (int)$report->status;
    $report->people = $people;

    return view("pages.reports.details.index", compact('userId', 'reportId', 'report'));
  }

  public function dtTable(Request $request) {
    $userId = $request->userId;

    $data = Report::select(
        "tbop_operacion.operacionid AS id",
        "tbop_operacion.userid AS userId",
        DB::raw("IFNULL((SELECT personanombres FROM tbop_personaoperacion  WHERE operacionid = tbop_operacion.operacionid LIMIT 1),'') AS name"),
        "tbop_tipo.tipodescrip as type",
        "tbop_opsenales.opsenalesdescrip as signal",
        "tbop_delito.delitodescrip as delit",
        "tbop_operacion.opoficina AS office",
        "tbop_urgencia.urgenciadescrip as urgency",
        "tbop_operacion.fechacreacion AS created_at",
        "tbop_operacion.estado AS state"
      )
      ->join('tbop_tipo', 'tbop_tipo.tipoid', '=', 'tbop_operacion.optipo')
      ->join('tbop_delito', 'tbop_delito.delitoid', '=', 'tbop_operacion.opdelito')
      ->join('tbop_urgencia', 'tbop_urgencia.urgenciaid', '=', 'tbop_operacion.opurgencia')
      ->join('tbop_opsenales', 'tbop_opsenales.opsenalesid', '=', 'tbop_operacion.opsenales')
      ->where('tbop_operacion.userid', '=', $userId)
      ->orWhereJsonContains('userIds', [$request->userId])
      ->orderBy('tbop_operacion.operacionid', 'desc')
      ->get();

    return datatables()->of($data)
      ->addIndexColumn()
      ->addColumn('status', function ($item) {
        return $item->state;
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

  public function create(Request $request) {
    $userId = $request->userId;
    $status = $request->status;

    $report = new Report();
    $report->userid = $userId;
    $report->fechacreacion = date("Y-m-d h:i:s");
    $report->estado = $status;

    $report->optipo           = $request->typeId;
    $report->opurgencia       = $request->urgencyId;
    $report->opoficina        = $request->office;
    $report->opdireccion      = $request->address;
    $report->opdepartamento   = $request->departmentId;
    $report->opprovincia      = $request->provinceId;
    $report->opdistrito       = $request->districtId;
    $report->optsenal         = $request->signaltypeId;
    $report->opsenales        = $request->signalIds;
    $report->opdelito         = $request->crimeId;
    $report->delitosoperacion = $request->crimetypeIds;
    $report->opactividad      = $request->activityId;
    $report->opdetalle        = $request->details;
    $report->opproducto       = $request->product;
    $report->opmonto          = $request->amount;
    $report->opmoneda         = $request->currencyId;
    $report->opfechadesde     = $request->startedAt;
    $report->opfechahasta     = $request->finishedAt;
    $report->opadicional      = $request->extra;

    $report->save();

    return $report;
  }

  public function createListPeople(Request $request) {
    $persons = $request->persons;
    $reportId = $request->reportId;

    OperationPeople::where('operacionid', '=', $reportId)->delete();

    foreach ($persons as $person) {
      $people = new OperationPeople();
      $people->operacionid        = $reportId;
      $people->personatiponj      = $person['henry'];
      $people->personatipo        = $person['peopletypeId'];
      $people->personacond        = $person['conditionId'];
      $people->personarazon       = $person['company'];
      $people->personaruc         = $person['ruc'];
      $people->personaapellidopat = $person['lastname1'];
      $people->personaapellidomat = $person['lastname2'];
      $people->personanombres     = $person['name'];
      $people->personafechanac    = $person['birthday'];
      $people->personanac         = $person['nationality'];
      $people->personapep         = $person['pepId'];
      $people->personatipodoc     = $person['documenttypeId'];
      $people->personadocumento   = $person['documentnumber'];
      $people->personaprof        = $person['ocupationId'];
      $people->personatelef       = $person['cellphone'];
      $people->personaemail       = $person['email'];
      $people->personadomicilio   = $person['address'];
      $people->personapais        = $person['countryId'];
      $people->personadep         = $person['departmentId'];
      $people->personaprov        = $person['provinceId'];
      $people->personadist        = $person['districtId'];
      $people->save();
    }

    return true;
  }

  public function modify(Request $request) {
    $id = $request->idReport;
    $status = $request->status;

    $report = Report::find($id);
    $report->estado = $status;

    $report->optipo           = $request->typeId;
    $report->opurgencia       = $request->urgencyId;
    $report->opoficina        = $request->office;
    $report->opdireccion      = $request->address;
    $report->opdepartamento   = $request->departmentId;
    $report->opprovincia      = $request->provinceId;
    $report->opdistrito       = $request->districtId;
    $report->optsenal         = $request->signaltypeId;
    $report->opsenales        = $request->signalIds;
    $report->opdelito         = $request->crimeId;
    $report->delitosoperacion = $request->crimetypeIds;
    $report->opactividad      = $request->activityId;
    $report->opdetalle        = $request->details;
    $report->opproducto       = $request->product;
    $report->opmonto          = $request->amount;
    $report->opmoneda         = $request->currencyId;
    $report->opfechadesde     = $request->startedAt;
    $report->opfechahasta     = $request->finishedAt;
    $report->opadicional      = $request->extra;

    $report->save();

    return $report;
  }

  public function export($id) {
    $report = Report::select(
        "wp_users.display_name              as user",
        "tbop_operacion.fechacreacion       as createdAt",
        // "tbop_operacion.estado              as status",
  
        "tbop_tipo.tipodescrip              as type",
        "tbop_urgencia.urgenciadescrip      as urgency",
        "tbop_operacion.opoficina           as office",
        "tbop_operacion.opdireccion         as address",
        "tbop_departamento.nombre           as department",
        "tbop_provincia.nombre              as province",
        "tbop_distrito.nombre               as district",
        "tbop_tsenal.senaldescrip           as signalType",
        "tbop_opsenales.opsenalesdescrip    as signals",
        "tbop_delito.delitodescrip          as crime",
        "tbop_tipodelito.tipodelitodescrip  as crimetypeIds",
        "tbop_actividad.actividaddescrip    as activity",
        "tbop_operacion.opdetalle           as details",
        "tbop_operacion.opproducto          as product",
        "tbop_operacion.opmonto             as amount",
        "tbop_moneda.monedadescrip          as currency",
        "tbop_operacion.opfechadesde        as startedAt",
        "tbop_operacion.opfechahasta        as finishedAt",
        "tbop_operacion.opadicional         as extra"
      )
      ->leftJoin("wp_users", "wp_users.ID", "=", "tbop_operacion.userid")
      ->leftJoin("tbop_tipo", "tbop_tipo.tipoid", "=", "tbop_operacion.optipo")
      ->leftJoin("tbop_urgencia", "tbop_urgencia.urgenciaid", "=", "tbop_operacion.opurgencia")
      ->leftJoin("tbop_departamento", "tbop_departamento.id", "=", "tbop_operacion.opdepartamento")
      ->leftJoin("tbop_provincia", "tbop_provincia.id", "=", "tbop_operacion.opprovincia")
      ->leftJoin("tbop_distrito", "tbop_distrito.id", "=", "tbop_operacion.opdistrito")
      ->leftJoin("tbop_tsenal", "tbop_tsenal.senalid", "=", "tbop_operacion.optsenal")
      ->leftJoin("tbop_opsenales", "tbop_opsenales.opsenalesid", "=", "tbop_operacion.opsenales")
      ->leftJoin("tbop_delito", "tbop_delito.delitoid", "=", "tbop_operacion.opdelito")
      ->leftJoin("tbop_tipodelito", "tbop_tipodelito.tipodelitoid", "=", "tbop_operacion.delitosoperacion")
      ->leftJoin("tbop_actividad", "tbop_actividad.actividadid", "=", "tbop_operacion.opactividad")
      ->leftJoin("tbop_moneda", "tbop_moneda.monedaid", "=", "tbop_operacion.opmoneda")
      ->where('tbop_operacion.operacionid', '=', $id)
      ->first();

    $response = [
      "Tipo" => $report ? $report->type : '',
      "Alerta" => $report ? $report->urgency : '',
      "Oficina" => $report ? $report->office : '',
      "Direccion" => $report ? $report->address : '',
      "Departamento" => $report ? $report->department : '',
      "Provincia" => $report ? $report->province : '',
      "Distrito" => $report ? $report->district : '',
      "TipoDeSenal" => $report ? $report->signalType : '',
      "Senales" => $report ? $report->signals : '',
      "Delito" => $report ? $report->crime : '',
      "TipoDelito" => $report ? $report->crimetypeIds : '',
      "Actividad" => $report ? $report->activity : '',
      "Detalles" => $report ? $report->details : '',
      "Producto" => $report ? $report->product : '',
      "Monto" => $report ? $report->amount : '',
      "Moneda" => $report ? $report->currency : '',
      "IniciadoEn" => $report ? $report->startedAt : '',
      "FinalizadoEn" => $report ? $report->finishedAt : '',
      "Extra" => $report ? $report->extra : '',
    ];

    $people = OperationPeople::select(
        // "peropid            as id",
        // "operacionid        as reportId",
        "tbop_personaoperacion.personatiponj        as henry",
        "tbop_tipopersona.tipopersonadescrip        as peopletype",
        "tbop_cond.conddescrip                      as condition",
        "tbop_personaoperacion.personarazon         as company",
        "tbop_personaoperacion.personaruc           as ruc",
        "tbop_personaoperacion.personaapellidopat   as lastname1",
        "tbop_personaoperacion.personaapellidomat   as lastname2",
        "tbop_personaoperacion.personanombres       as name",
        "tbop_personaoperacion.personafechanac      as birthday",
        // "tbop_personaoperacion.personanac           as nationality",
        DB::raw("(SELECT nacionalidaddescrip FROM tbop_nacionalidad  WHERE nacionalidadid = tbop_personaoperacion.personanac LIMIT 1) as nationality"),
        "tbop_personaoperacion.personapep           as pep",
        "tbop_tipodoc.tipodocdescrip                as documenttype",
        "tbop_personaoperacion.personadocumento     as documentnumber",
        "tbop_prof.profdescrip                      as ocupation",
        "tbop_personaoperacion.personatelef         as cellphone",
        "tbop_personaoperacion.personaemail         as email",
        "tbop_personaoperacion.personadomicilio     as address",
        "tbop_nacionalidad.nacionalidaddescrip      as country",
        "tbop_departamento.nombre                   as department",
        "tbop_provincia.nombre                      as province",
        "tbop_distrito.nombre                       as district",
      )
      ->join("tbop_tipopersona", "tbop_tipopersona.tipopersonaid", "=", "tbop_personaoperacion.personatipo")
      ->join("tbop_cond", "tbop_cond.condid", "=", "tbop_personaoperacion.personacond")
      ->join("tbop_tipodoc", "tbop_tipodoc.tipodocid", "=", "tbop_personaoperacion.personatipodoc")
      ->join("tbop_prof", "tbop_prof.profid", "=", "tbop_personaoperacion.personaprof")
      ->join("tbop_nacionalidad", "tbop_nacionalidad.nacionalidadid", "=", "tbop_personaoperacion.personapais")
      ->join("tbop_departamento", "tbop_departamento.id", "=", "tbop_personaoperacion.personadep")
      ->join("tbop_provincia", "tbop_provincia.id", "=", "tbop_personaoperacion.personaprov")
      ->join("tbop_distrito", "tbop_distrito.id", "=", "tbop_personaoperacion.personadist")
      ->where('operacionid', '=', $id)
      ->get();

    // $report->status = (int)$report->status;
    // $report->people = $people;

    $i = 1;
    foreach ($people as $person) {
      // $key = "person_" . $i;
      // $response[$key] = [
        $response["PNPJ_{$i}"] = $person->henry;
        $response["TipoPersona_{$i}"] = $person->peopletype;
        $response["Condicion_{$i}"] = $person->condition;
        $response["Empresa_{$i}"] = $person->company;
        $response["RUC_{$i}"] = $person->ruc;
        $response["ApellidoPaterno_{$i}"] = $person->lastname1;
        $response["ApellidoMaterno_{$i}"] = $person->lastname2;
        $response["Nombres_{$i}"] = $person->name;
        $response["FechaDeNacimiento_{$i}"] = $person->birthday;
        $response["Nacionalidad_{$i}"] = $person->nationality;
        $response["PEP_{$i}"] = $person->pep;
        $response["TipoDocumento_{$i}"] = $person->documenttype;
        $response["NumeroDocumento_{$i}"] = $person->documentnumber;
        $response["Profesion_{$i}"] = $person->ocupation;
        $response["Celular_{$i}"] = $person->cellphone;
        $response["Email_{$i}"] = $person->email;
        $response["Direccion_{$i}"] = $person->address;
        $response["Pais_{$i}"] = $person->country;
        $response["Departamento_{$i}"] = $person->department;
        $response["Provincia_{$i}"] = $person->province;
        $response["Distrito_{$i}"] = $person->district;
      // ];
      $i++;
    }

    $excel = new FastExcel([$response]);

    $generatedAt = date("Ymd");
    $filename = "Resultado$generatedAt.xlsx";

    // return $report;
    return ($excel)->download($filename);
    // return $response;
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
    $reportId = $request->reportId;

    $report = Report::find($reportId);
    $report->userIds = $this->str2lst($report->userIds);

    $userIds = $report->userIds;
    if (in_array($userId, $userIds)) {
      return $report;
    }
    $userIds[] = strval($userId);
    $report->userIds = $userIds;
    $report->save();

    return $report;
  }
}
