<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Operation;
use App\Models\OperationBeneficiary;

use Illuminate\Http\Request;

use DB;
use Session;
use Rap2hpoutre\FastExcel\FastExcel;
use PHPExcel; 
use PHPExcel_IOFactory;

class OperationController extends Controller {
  public function viewList() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $userId = Session::get("loginId");

    return view("pages.operations.index", compact('userId'));
  }

  public function dtTable(Request $request) {
    $userId = $request->userId;

    $data = Operation::select(
        "regoperacionid AS id",
        "opnroregistro_uno AS number",
        DB::raw("CONCAT(opnombres_dos, ', ', opapellidos_dos) AS people_1"),
        DB::raw("CONCAT(opnombres_tres, ', ', opapellidos_tres) AS people_2"),
        "opoficina_uno AS office",
        "opfecharegistro_uno AS registered_at",
        "fechacreacion AS created_at",
        DB::raw("(SELECT COUNT(*) FROM tbop_beneficiariooperacion tb WHERE tb.regoperacionid = tbop_regoperaciones.regoperacionid) AS benef"),
        "estado AS state"
      )
      ->where('userid', '=', $userId)
      ->where('opempresa_uno', '!=', '')
      ->where('opnroregistro_uno', '!=', '')
      ->orWhereJsonContains('userIds', [$request->userId])
      ->orderBy('regoperacionid', 'desc')
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

  public function viewGenerate() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }
    $userId = Session::get("loginId");
  
    return view("pages.operations.generate.index", compact('userId'));
  }

  public function viewDetails($id) {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }
    $userId = Session::get("loginId");
    $operationId = $id;

    $operation = Operation::select(
        "userid                   as userId",
        "fechacreacion            as createdAt",
        "estado                   as status",
  
        "opempresa_uno            as company",
        "opnroregistro_uno        as code",
        "opoficina_uno            as office",
        "opfecharegistro_uno      as registeredAt",
  
        "opapellidos_dos          as lastname1",
        "opnombres_dos            as name1",
        "opfechanac_dos           as birthday1",
        "opnacionalidad_dos       as nationality1",
        "opprofesion_dos          as ocupation1",
        "opdomicilio_dos          as address1",
        "opcodigopostal_dos       as postalcode1",
        "opprovincia_dos          as province1",
        "opdepartamento_dos       as department1",
        "oppais_dos               as country1",
        "optelefono_dos           as cellphone1",
        "opdni_dos                as dni1",
        "oprut_dos                as rut1",
        "oplm_dos                 as lm1",
        "opci_dos                 as ci1",
        "opce_dos                 as ce1",
        "oppasaporte_dos          as passport1",
        "opermitido_dos           as emittedAt1",
        "opruc_dos                as ruc1",
        "opotro_dos               as other1",
  
        "opapellidos_tres         as lastname2",
        "opnombres_tres           as name2",
        "opfechanac_tres          as birthday2",
        "opnacionalidad_tres      as nationality2",
        "opprofesion_tres         as ocupation2",
        "opdomicilio_tres         as address2",
        "opcodigopostal_tres      as postalcode2",
        "opprovincia_tres         as province2",
        "opdepartamento_tres      as department2",
        "oppais_tres              as country2",
        "optelefono_tres          as cellphone2",
        "opdni_tres               as dni2",
        "oprut_tres               as rut2",
        "oplm_tres                as lm2",
        "opci_tres                as ci2",
        "opce_tres                as ce2",
        "oppasaporte_tres         as passport2",
        "opermitido_tres          as emittedAt2",
        "opruc_tres               as ruc2",
        "opotro_tres              as other2",
  
        "opbeneficiario1          as beneficiary",
  
        "opapellidos_cuatro       as lastname3",
        "opnombres_cuatro         as name3",
        "opfechanac_cuatro        as birthday3",
        "opnacionalidad_cuatro    as nationality3",
        "opprofesion_cuatro       as ocupation3",
        "opdomicilio_cuatro       as address3",
        "opcodigopostal_cuatro    as postalcode3",
        "opprovincia_cuatro       as province3",
        "opdepartamento_cuatro    as department3",
        "oppais_cuatro            as country3",
        "optelefono_cuatro        as cellphone3",
        "opdni_cuatro             as dni3",
        "oprut_cuatro             as rut3",
        "oplm_cuatro              as lm3",
        "opci_cuatro              as ci3",
        "opce_cuatro              as ce3",
        "oppasaporte_cuatro       as passport3",
        "opermitido_cuatro        as emittedAt3",
        "opruc_cuatro             as ruc3",
        "opotro_cuatro            as other3",
  
        "opmontooperaciondolares  as amount",
        "opfechaoperacion         as date",
        "oplugarrealizacion       as location",
  
        "opmonedanacional         as nationalcurrency",
        "opmonedaextranjera       as foreigncurrency",
        "opespecificarmoneda      as foreigncurrencyDetails",
        "opchequegerencia         as cashierscheck",
        "opchequeviajero          as travelerscheck",
        "opordenespago            as paymentorder",
        "opotros_uno              as otherp",
        "opespecificar_uno        as otherDetailsp",
  
        "opcompravalores          as buy",
        "opventavalores           as sell",
        "opasesorias              as consultancies",
        "opcolocacionesprimarias  as primaryplacements",
        "opadministracioncartera  as portfoliomanagement",
        "opcustodiavalores        as custody",
        "opmutuosdinero           as mutualmoney",
        "opprestamovalores        as loan",
        "opfondosmutuos           as mutualfunds",
        "opfondosinversion        as investmentfunds",
        "opderivados              as derivatives",
        "opfondoscolectivos       as collectivefunds",
        "opotros_dos              as otherq",
        "opespecificar_dos        as otherDetailsq",
  
        "opcuenta_uno             as account1",
        "opcuenta_dos             as account2",
        "opcuenta_tres            as account3"
      )
      ->where('regoperacionid', '=', $id)
      ->first();

    $people = OperationBeneficiary::select(
        "beneopid           as id",
        "regoperacionid     as operationId",
        "beneape            as lastname",
        "benenom            as name",
        "benefecha          as birthday",
        "benenac            as nationality",
        "beneprofesion      as ocupation",
        "benecp             as postalcode",
        "benepais           as country",
        "benedepto          as department",
        "beneprov           as province",
        "benetelf           as cellphone",
        "benedireccion      as address",
        "benedni            as dni",
        "benerut            as rut",
        "benelm             as lm",
        "beneci             as ci",
        "benece             as ce",
        "benepasaporte      as passport",
        "beneemitido        as emittedAt",
        "beneruc            as ruc",
        "beneotro           as other",
      )
      ->where('regoperacionid', '=', $id)
      ->get();

    $operation->status = (int)$operation->status;
    $operation->people = $people;

    return view("pages.operations.details.index", compact('userId', 'operationId', 'operation'));
  }

  public function create(Request $request) {
    $s1 = $request->s1;
    $s2 = $request->s2;
    $s3 = $request->s3;
    $s4 = $request->s4;
    $s5 = $request->s5;
    $userId = $request->userId;
    $status = $request->status;

    $operation = new Operation();
    $operation->userid = $userId;
    $operation->fechacreacion = date("Y-m-d h:i:s");
    $operation->estado = $status;

    $operation->opempresa_uno       = isset($s1['company']) ? $s1['company'] : '';
    $operation->opnroregistro_uno   = isset($s1['code']) ? $s1['code'] : '';
    $operation->opoficina_uno       = isset($s1['office']) ? $s1['office'] : '';
    $operation->opfecharegistro_uno = isset($s1['registeredAt']) ? $s1['registeredAt'] : '';

    $operation->opapellidos_dos     = isset($s2['lastname']) ? $s2['lastname'] : '';
    $operation->opnombres_dos       = isset($s2['name']) ? $s2['name'] : '';
    $operation->opfechanac_dos      = isset($s2['birthday']) ? $s2['birthday'] : '';
    $operation->opnacionalidad_dos  = isset($s2['nationality']) ? $s2['nationality'] : '';
    $operation->opprofesion_dos     = isset($s2['ocupation']) ? $s2['ocupation'] : '';
    $operation->opdomicilio_dos     = isset($s2['address']) ? $s2['address'] : '';
    $operation->opcodigopostal_dos  = isset($s2['postalcode']) ? $s2['postalcode'] : '';
    $operation->opprovincia_dos     = isset($s2['province']) ? $s2['province'] : '';
    $operation->opdepartamento_dos  = isset($s2['department']) ? $s2['department'] : '';
    $operation->oppais_dos          = isset($s2['country']) ? $s2['country'] : '';
    $operation->optelefono_dos      = isset($s2['cellphone']) ? $s2['cellphone'] : '';
    // $operation->optipodocumento_dos = $request->;
    $operation->opdni_dos           = isset($s2['dni']) ? $s2['dni'] : '';
    $operation->oprut_dos           = isset($s2['rut']) ? $s2['rut'] : '';
    $operation->oplm_dos            = isset($s2['lm']) ? $s2['lm'] : '';
    $operation->opci_dos            = isset($s2['ci']) ? $s2['ci'] : '';
    $operation->opce_dos            = isset($s2['ce']) ? $s2['ce'] : '';
    $operation->oppasaporte_dos     = isset($s2['passport']) ? $s2['passport'] : '';
    $operation->opermitido_dos      = isset($s2['emittedAt']) ? $s2['emittedAt'] : '';
    $operation->opruc_dos           = isset($s2['ruc']) ? $s2['ruc'] : '';
    $operation->opotro_dos          = isset($s2['other']) ? $s2['other'] : '';

    $operation->opapellidos_tres    = isset($s3['lastname']) ? $s3['lastname'] : '';
    $operation->opnombres_tres      = isset($s3['name']) ? $s3['name'] : '';
    $operation->opfechanac_tres     = isset($s3['birthday']) ? $s3['birthday'] : '';
    $operation->opnacionalidad_tres = isset($s3['nationality']) ? $s3['nationality'] : '';
    $operation->opprofesion_tres    = isset($s3['ocupation']) ? $s3['ocupation'] : '';
    $operation->opdomicilio_tres    = isset($s3['address']) ? $s3['address'] : '';
    $operation->opcodigopostal_tres = isset($s3['postalcode']) ? $s3['postalcode'] : '';
    $operation->opprovincia_tres    = isset($s3['province']) ? $s3['province'] : '';
    $operation->opdepartamento_tres = isset($s3['department']) ? $s3['department'] : '';
    $operation->oppais_tres         = isset($s3['country']) ? $s3['country'] : '';
    $operation->optelefono_tres     = isset($s3['cellphone']) ? $s3['cellphone'] : '';
    // $operation->optipodocumento_tres = $request->;
    $operation->opdni_tres          = isset($s3['dni']) ? $s3['dni'] : '';
    $operation->oprut_tres          = isset($s3['rut']) ? $s3['rut'] : '';
    $operation->oplm_tres           = isset($s3['lm']) ? $s3['lm'] : '';
    $operation->opci_tres           = isset($s3['ci']) ? $s3['ci'] : '';
    $operation->opce_tres           = isset($s3['ce']) ? $s3['ce'] : '';
    $operation->oppasaporte_tres    = isset($s3['passport']) ? $s3['passport'] : '';
    $operation->opermitido_tres     = isset($s3['emittedAt']) ? $s3['emittedAt'] : '';
    $operation->opruc_tres          = isset($s3['ruc']) ? $s3['ruc'] : '';
    $operation->opotro_tres         = isset($s3['other']) ? $s3['other'] : '';

    $operation->opbeneficiario1     = isset($s4['beneficiary']) ? $s4['beneficiary'] : '';

    $operation->opapellidos_cuatro    = isset($s4['lastname']) ? $s4['lastname'] : '';
    $operation->opnombres_cuatro      = isset($s4['name']) ? $s4['name'] : '';
    $operation->opfechanac_cuatro     = isset($s4['birthday']) ? $s4['birthday'] : '';
    $operation->opnacionalidad_cuatro = isset($s4['nationality']) ? $s4['nationality'] : '';
    $operation->opprofesion_cuatro    = isset($s4['ocupation']) ? $s4['ocupation'] : '';
    $operation->opdomicilio_cuatro    = isset($s4['address']) ? $s4['address'] : '';
    $operation->opcodigopostal_cuatro = isset($s4['postalcode']) ? $s4['postalcode'] : '';
    $operation->opprovincia_cuatro    = isset($s4['province']) ? $s4['province'] : '';
    $operation->opdepartamento_cuatro = isset($s4['department']) ? $s4['department'] : '';
    $operation->oppais_cuatro         = isset($s4['country']) ? $s4['country'] : '';
    $operation->optelefono_cuatro     = isset($s4['cellphone']) ? $s4['cellphone'] : '';
    // $operation->optipodocumento_cuatro = $request->;
    $operation->opdni_cuatro          = isset($s4['dni']) ? $s4['dni'] : '';
    $operation->oprut_cuatro          = isset($s4['rut']) ? $s4['rut'] : '';
    $operation->oplm_cuatro           = isset($s4['lm']) ? $s4['lm'] : '';
    $operation->opci_cuatro           = isset($s4['ci']) ? $s4['ci'] : '';
    $operation->opce_cuatro           = isset($s4['ce']) ? $s4['ce'] : '';
    $operation->oppasaporte_cuatro    = isset($s4['passport']) ? $s4['passport'] : '';
    $operation->opermitido_cuatro     = isset($s4['emittedAt']) ? $s4['emittedAt'] : '';
    $operation->opruc_cuatro          = isset($s4['ruc']) ? $s4['ruc'] : '';
    $operation->opotro_cuatro         = isset($s4['other']) ? $s4['other'] : '';

    $operation->opmontooperaciondolares = isset($s5['amount']) ? $s5['amount'] : '';
    $operation->opfechaoperacion        = isset($s5['date']) ? $s5['date'] : '';
    $operation->oplugarrealizacion      = isset($s5['location']) ? $s5['location'] : '';

    $operation->opmonedanacional        = isset($s5['nationalcurrency']) ? $s5['nationalcurrency'] : '';
    $operation->opmonedaextranjera      = isset($s5['foreigncurrency']) ? $s5['foreigncurrency'] : '';
    $operation->opespecificarmoneda     = isset($s5['foreigncurrencyDetails']) ? $s5['foreigncurrencyDetails'] : '';
    $operation->opchequegerencia        = isset($s5['cashierscheck']) ? $s5['cashierscheck'] : '';
    $operation->opchequeviajero         = isset($s5['travelerscheck']) ? $s5['travelerscheck'] : '';
    $operation->opordenespago           = isset($s5['paymentorder']) ? $s5['paymentorder'] : '';
    $operation->opotros_uno             = isset($s5['otherP']) ? $s5['otherP'] : '';
    $operation->opespecificar_uno       = isset($s5['otherDetailsP']) ? $s5['otherDetailsP'] : '';

    $operation->opcompravalores         = isset($s5['buy']) ? $s5['buy'] : '';
    $operation->opventavalores          = isset($s5['sell']) ? $s5['sell'] : '';
    $operation->opasesorias             = isset($s5['consultancies']) ? $s5['consultancies'] : '';
    $operation->opcolocacionesprimarias = isset($s5['primaryplacements']) ? $s5['primaryplacements'] : '';
    $operation->opadministracioncartera = isset($s5['portfoliomanagement']) ? $s5['portfoliomanagement'] : '';
    $operation->opcustodiavalores       = isset($s5['custody']) ? $s5['custody'] : '';
    $operation->opmutuosdinero          = isset($s5['mutualmoney']) ? $s5['mutualmoney'] : '';
    $operation->opprestamovalores       = isset($s5['loan']) ? $s5['loan'] : '';
    $operation->opfondosmutuos          = isset($s5['mutualfunds']) ? $s5['mutualfunds'] : '';
    $operation->opfondosinversion       = isset($s5['investmentfunds']) ? $s5['investmentfunds'] : '';
    $operation->opderivados             = isset($s5['derivatives']) ? $s5['derivatives'] : '';
    $operation->opfondoscolectivos      = isset($s5['collectivefunds']) ? $s5['collectivefunds'] : '';
    $operation->opotros_dos             = isset($s5['otherQ']) ? $s5['otherQ'] : '';
    $operation->opespecificar_dos       = isset($s5['otherDetailsQ']) ? $s5['otherDetailsQ'] : '';

    $operation->opcuenta_uno            = isset($s5['account1']) ? $s5['account1'] : '';
    $operation->opcuenta_dos            = isset($s5['account2']) ? $s5['account2'] : '';
    $operation->opcuenta_tres           = isset($s5['account3']) ? $s5['account3'] : '';
    // $operation->opcuenta_cuatro = $request->;

    $operation->save();

    return $operation;
  }

  public function createListBeneficiaries(Request $request) {
    $persons = $request->persons;
    $operationId = $request->operationId;

    OperationBeneficiary::where('regoperacionid', '=', $operationId)->delete();

    foreach ($persons as $person) {
      $beneficiary = new OperationBeneficiary();
      $beneficiary->regoperacionid  = $operationId;
      $beneficiary->beneape         = isset($person['lastname']) ? $person['lastname'] : '';
      $beneficiary->benenom         = isset($person['name']) ? $person['name'] : '';
      $beneficiary->benefecha       = isset($person['birthday']) ? $person['birthday'] : '';
      $beneficiary->benenac         = isset($person['nationality']) ? $person['nationality'] : '';
      $beneficiary->beneprofesion   = isset($person['ocupation']) ? $person['ocupation'] : '';
      $beneficiary->benecp          = isset($person['postalcode']) ? $person['postalcode'] : '';
      $beneficiary->benepais        = isset($person['country']) ? $person['country'] : '';
      $beneficiary->benedepto       = isset($person['department']) ? $person['department'] : '';
      $beneficiary->beneprov        = isset($person['province']) ? $person['province'] : '';
      $beneficiary->benetelf        = isset($person['cellphone']) ? $person['cellphone'] : '';
      $beneficiary->benedireccion   = isset($person['address']) ? $person['address'] : '';
      $beneficiary->benedni         = isset($person['dni']) ? $person['dni'] : '';
      $beneficiary->benerut         = isset($person['rut']) ? $person['rut'] : '';
      $beneficiary->benelm          = isset($person['lm']) ? $person['lm'] : '';
      $beneficiary->beneci          = isset($person['ci']) ? $person['ci'] : '';
      $beneficiary->benece          = isset($person['ce']) ? $person['ce'] : '';
      $beneficiary->benepasaporte   = isset($person['passport']) ? $person['passport'] : '';
      $beneficiary->beneemitido     = isset($person['emittedAt']) ? $person['emittedAt'] : '';
      $beneficiary->beneruc         = isset($person['ruc']) ? $person['ruc'] : '';
      $beneficiary->beneotro        = isset($person['other']) ? $person['other'] : '';
      $beneficiary->save();
    }

    return true;
  }

  public function modify(Request $request) {
    $id = $request->idOperation;
    $s1 = $request->s1;
    $s2 = $request->s2;
    $s3 = $request->s3;
    $s4 = $request->s4;
    $s5 = $request->s5;
    $status = $request->status;

    $operation = Operation::find($id);
    $operation->estado = $status;

    $operation->opempresa_uno       = isset($s1['company']) ? $s1['company'] : '';
    $operation->opnroregistro_uno   = isset($s1['code']) ? $s1['code'] : '';
    $operation->opoficina_uno       = isset($s1['office']) ? $s1['office'] : '';
    $operation->opfecharegistro_uno = isset($s1['registeredAt']) ? $s1['registeredAt'] : '';

    $operation->opapellidos_dos     = isset($s2['lastname']) ? $s2['lastname'] : '';
    $operation->opnombres_dos       = isset($s2['name']) ? $s2['name'] : '';
    $operation->opfechanac_dos      = isset($s2['birthday']) ? $s2['birthday'] : '';
    $operation->opnacionalidad_dos  = isset($s2['nationality']) ? $s2['nationality'] : '';
    $operation->opprofesion_dos     = isset($s2['ocupation']) ? $s2['ocupation'] : '';
    $operation->opdomicilio_dos     = isset($s2['address']) ? $s2['address'] : '';
    $operation->opcodigopostal_dos  = isset($s2['postalcode']) ? $s2['postalcode'] : '';
    $operation->opprovincia_dos     = isset($s2['province']) ? $s2['province'] : '';
    $operation->opdepartamento_dos  = isset($s2['department']) ? $s2['department'] : '';
    $operation->oppais_dos          = isset($s2['country']) ? $s2['country'] : '';
    $operation->optelefono_dos      = isset($s2['cellphone']) ? $s2['cellphone'] : '';
    // $operation->optipodocumento_dos = $request->;
    $operation->opdni_dos           = isset($s2['dni']) ? $s2['dni'] : '';
    $operation->oprut_dos           = isset($s2['rut']) ? $s2['rut'] : '';
    $operation->oplm_dos            = isset($s2['lm']) ? $s2['lm'] : '';
    $operation->opci_dos            = isset($s2['ci']) ? $s2['ci'] : '';
    $operation->opce_dos            = isset($s2['ce']) ? $s2['ce'] : '';
    $operation->oppasaporte_dos     = isset($s2['passport']) ? $s2['passport'] : '';
    $operation->opermitido_dos      = isset($s2['emittedAt']) ? $s2['emittedAt'] : '';
    $operation->opruc_dos           = isset($s2['ruc']) ? $s2['ruc'] : '';
    $operation->opotro_dos          = isset($s2['other']) ? $s2['other'] : '';

    $operation->opapellidos_tres    = isset($s3['lastname']) ? $s3['lastname'] : '';
    $operation->opnombres_tres      = isset($s3['name']) ? $s3['name'] : '';
    $operation->opfechanac_tres     = isset($s3['birthday']) ? $s3['birthday'] : '';
    $operation->opnacionalidad_tres = isset($s3['nationality']) ? $s3['nationality'] : '';
    $operation->opprofesion_tres    = isset($s3['ocupation']) ? $s3['ocupation'] : '';
    $operation->opdomicilio_tres    = isset($s3['address']) ? $s3['address'] : '';
    $operation->opcodigopostal_tres = isset($s3['postalcode']) ? $s3['postalcode'] : '';
    $operation->opprovincia_tres    = isset($s3['province']) ? $s3['province'] : '';
    $operation->opdepartamento_tres = isset($s3['department']) ? $s3['department'] : '';
    $operation->oppais_tres         = isset($s3['country']) ? $s3['country'] : '';
    $operation->optelefono_tres     = isset($s3['cellphone']) ? $s3['cellphone'] : '';
    // $operation->optipodocumento_tres = $request->;
    $operation->opdni_tres          = isset($s3['dni']) ? $s3['dni'] : '';
    $operation->oprut_tres          = isset($s3['rut']) ? $s3['rut'] : '';
    $operation->oplm_tres           = isset($s3['lm']) ? $s3['lm'] : '';
    $operation->opci_tres           = isset($s3['ci']) ? $s3['ci'] : '';
    $operation->opce_tres           = isset($s3['ce']) ? $s3['ce'] : '';
    $operation->oppasaporte_tres    = isset($s3['passport']) ? $s3['passport'] : '';
    $operation->opermitido_tres     = isset($s3['emittedAt']) ? $s3['emittedAt'] : '';
    $operation->opruc_tres          = isset($s3['ruc']) ? $s3['ruc'] : '';
    $operation->opotro_tres         = isset($s3['other']) ? $s3['other'] : '';

    $operation->opbeneficiario1     = isset($s4['beneficiary']) ? $s4['beneficiary'] : '';

    $operation->opapellidos_cuatro    = isset($s4['lastname']) ? $s4['lastname'] : '';
    $operation->opnombres_cuatro      = isset($s4['name']) ? $s4['name'] : '';
    $operation->opfechanac_cuatro     = isset($s4['birthday']) ? $s4['birthday'] : '';
    $operation->opnacionalidad_cuatro = isset($s4['nationality']) ? $s4['nationality'] : '';
    $operation->opprofesion_cuatro    = isset($s4['ocupation']) ? $s4['ocupation'] : '';
    $operation->opdomicilio_cuatro    = isset($s4['address']) ? $s4['address'] : '';
    $operation->opcodigopostal_cuatro = isset($s4['postalcode']) ? $s4['postalcode'] : '';
    $operation->opprovincia_cuatro    = isset($s4['province']) ? $s4['province'] : '';
    $operation->opdepartamento_cuatro = isset($s4['department']) ? $s4['department'] : '';
    $operation->oppais_cuatro         = isset($s4['country']) ? $s4['country'] : '';
    $operation->optelefono_cuatro     = isset($s4['cellphone']) ? $s4['cellphone'] : '';
    // $operation->optipodocumento_cuatro = $request->;
    $operation->opdni_cuatro          = isset($s4['dni']) ? $s4['dni'] : '';
    $operation->oprut_cuatro          = isset($s4['rut']) ? $s4['rut'] : '';
    $operation->oplm_cuatro           = isset($s4['lm']) ? $s4['lm'] : '';
    $operation->opci_cuatro           = isset($s4['ci']) ? $s4['ci'] : '';
    $operation->opce_cuatro           = isset($s4['ce']) ? $s4['ce'] : '';
    $operation->oppasaporte_cuatro    = isset($s4['passport']) ? $s4['passport'] : '';
    $operation->opermitido_cuatro     = isset($s4['emittedAt']) ? $s4['emittedAt'] : '';
    $operation->opruc_cuatro          = isset($s4['ruc']) ? $s4['ruc'] : '';
    $operation->opotro_cuatro         = isset($s4['other']) ? $s4['other'] : '';

    $operation->opmontooperaciondolares = isset($s5['amount']) ? $s5['amount'] : '';
    $operation->opfechaoperacion        = isset($s5['date']) ? $s5['date'] : '';
    $operation->oplugarrealizacion      = isset($s5['location']) ? $s5['location'] : '';

    $operation->opmonedanacional        = isset($s5['nationalcurrency']) ? $s5['nationalcurrency'] : '';
    $operation->opmonedaextranjera      = isset($s5['foreigncurrency']) ? $s5['foreigncurrency'] : '';
    $operation->opespecificarmoneda     = isset($s5['foreigncurrencyDetails']) ? $s5['foreigncurrencyDetails'] : '';
    $operation->opchequegerencia        = isset($s5['cashierscheck']) ? $s5['cashierscheck'] : '';
    $operation->opchequeviajero         = isset($s5['travelerscheck']) ? $s5['travelerscheck'] : '';
    $operation->opordenespago           = isset($s5['paymentorder']) ? $s5['paymentorder'] : '';
    $operation->opotros_uno             = isset($s5['otherP']) ? $s5['otherP'] : '';
    $operation->opespecificar_uno       = isset($s5['otherDetailsP']) ? $s5['otherDetailsP'] : '';

    $operation->opcompravalores         = isset($s5['buy']) ? $s5['buy'] : '';
    $operation->opventavalores          = isset($s5['sell']) ? $s5['sell'] : '';
    $operation->opasesorias             = isset($s5['consultancies']) ? $s5['consultancies'] : '';
    $operation->opcolocacionesprimarias = isset($s5['primaryplacements']) ? $s5['primaryplacements'] : '';
    $operation->opadministracioncartera = isset($s5['portfoliomanagement']) ? $s5['portfoliomanagement'] : '';
    $operation->opcustodiavalores       = isset($s5['custody']) ? $s5['custody'] : '';
    $operation->opmutuosdinero          = isset($s5['mutualmoney']) ? $s5['mutualmoney'] : '';
    $operation->opprestamovalores       = isset($s5['loan']) ? $s5['loan'] : '';
    $operation->opfondosmutuos          = isset($s5['mutualfunds']) ? $s5['mutualfunds'] : '';
    $operation->opfondosinversion       = isset($s5['investmentfunds']) ? $s5['investmentfunds'] : '';
    $operation->opderivados             = isset($s5['derivatives']) ? $s5['derivatives'] : '';
    $operation->opfondoscolectivos      = isset($s5['collectivefunds']) ? $s5['collectivefunds'] : '';
    $operation->opotros_dos             = isset($s5['otherQ']) ? $s5['otherQ'] : '';
    $operation->opespecificar_dos       = isset($s5['otherDetailsQ']) ? $s5['otherDetailsQ'] : '';

    $operation->opcuenta_uno            = isset($s5['account1']) ? $s5['account1'] : '';
    $operation->opcuenta_dos            = isset($s5['account2']) ? $s5['account2'] : '';
    $operation->opcuenta_tres           = isset($s5['account3']) ? $s5['account3'] : '';
    // $operation->opcuenta_cuatro = $request->;

    $operation->save();

    return $operation;
  }

  public function clean($val) {
    if (!is_object($val) && $val !== '') {
      $aux = str_replace("'", "\'", trim($val));
      // $aux = str_replace("/", "\/", $aux);

      /*$accents = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'];
      $normals = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];

      for ($i=0; $i<10; $i++) {
        $aux = str_replace($accents[$i], $normals[$i], $aux);
      }*/

      return $aux;
    }
    return $val;
  }

  public function massive(Request $request) {
    ini_set('max_execution_time', 120);

    $file = $request->file('file');
    $userId = $request->idUser;

    $collection = (new FastExcel)->sheet(1)->import($file);

    $maxRows = config('constants.excel.maxRowsNegLists');
    if (count($collection) >= $maxRows) {
      return back()->with("fail", __("validation.custom.scoring-massive.max__limit") . " (máx. $maxRows filas)");
    }

    $operations = [];
    $beneficiaries = [];
    foreach ($collection as $item) {
      $vals = array_values($item);
      if (count($vals) < 93) {
        return back()->with("fail", __("validation.custom.scoring-massive.file__crash"));
      }

      $s1_company = $this->clean($vals[0]);
      $s1_code = $this->clean($vals[1]);
      $s1_office = $this->clean($vals[2]);
      $s1_registeredAt = $this->clean($vals[3]);

      $s2_lastname = $this->clean($vals[4]);
      $s2_name = $this->clean($vals[5]);
      $s2_birthday = $this->clean($vals[15]);
      $s2_nationality = $this->clean($vals[16]);
      $s2_ocupation = $this->clean($vals[17]);
      $s2_address = $this->clean($vals[18]);
      $s2_postalcode = $this->clean($vals[19]);
      $s2_province = $this->clean($vals[20]);
      $s2_department = $this->clean($vals[21]);
      $s2_country = $this->clean($vals[22]);
      $s2_cellphone = $this->clean($vals[23]);
      $s2_dni = $this->clean($vals[6]);
      $s2_rut = $this->clean($vals[7]);
      $s2_lm = $this->clean($vals[8]);
      $s2_ci = $this->clean($vals[9]);
      $s2_ce = $this->clean($vals[10]);
      $s2_passport = $this->clean($vals[11]);
      $s2_emittedAt = $this->clean($vals[12]);
      $s2_ruc = $this->clean($vals[13]);
      $s2_other = $this->clean($vals[14]);

      $s3_lastname = $this->clean($vals[24]);
      $s3_name = $this->clean($vals[25]);
      $s3_birthday = $this->clean($vals[35]);
      $s3_nationality = $this->clean($vals[36]);
      $s3_ocupation = $this->clean($vals[37]);
      $s3_address = $this->clean($vals[38]);
      $s3_postalcode = $this->clean($vals[39]);
      $s3_province = $this->clean($vals[40]);
      $s3_department = $this->clean($vals[41]);
      $s3_country = $this->clean($vals[42]);
      $s3_cellphone = $this->clean($vals[43]);
      $s3_dni = $this->clean($vals[26]);
      $s3_rut = $this->clean($vals[27]);
      $s3_lm = $this->clean($vals[28]);
      $s3_ci = $this->clean($vals[29]);
      $s3_ce = $this->clean($vals[30]);
      $s3_passport = $this->clean($vals[31]);
      $s3_emittedAt = $this->clean($vals[32]);
      $s3_ruc = $this->clean($vals[33]);
      $s3_other = $this->clean($vals[34]);

      $s4_beneficiary = $this->clean($vals[44]);

      $s4_lastname = $this->clean($vals[45]);
      $s4_name = $this->clean($vals[46]);
      $s4_birthday = $this->clean($vals[56]);
      $s4_nationality = $this->clean($vals[57]);
      $s4_ocupation = $this->clean($vals[58]);
      $s4_address = $this->clean($vals[59]);
      $s4_postalcode = $this->clean($vals[60]);
      $s4_province = $this->clean($vals[61]);
      $s4_department = $this->clean($vals[62]);
      $s4_country = $this->clean($vals[63]);
      $s4_cellphone = $this->clean($vals[64]);
      $s4_dni = $this->clean($vals[47]);
      $s4_rut = $this->clean($vals[48]);
      $s4_lm = $this->clean($vals[49]);
      $s4_ci = $this->clean($vals[50]);
      $s4_ce = $this->clean($vals[51]);
      $s4_passport = $this->clean($vals[52]);
      $s4_emittedAt = $this->clean($vals[53]);
      $s4_ruc = $this->clean($vals[54]);
      $s4_other = $this->clean($vals[55]);

      $s5_amount = $this->clean($vals[65]);
      $s5_date = $this->clean($vals[66]);
      $s5_location = $this->clean($vals[67]);

      $s5_nationalcurrency = $this->clean($vals[68]);
      $s5_foreigncurrency = $this->clean($vals[69]);
      $s5_foreigncurrencyDetails = $this->clean($vals[70]);
      $s5_cashierscheck = $this->clean($vals[71]);
      $s5_travelerscheck = $this->clean($vals[72]);
      $s5_paymentorder = $this->clean($vals[73]);
      $s5_otherP = $this->clean($vals[74]);
      $s5_otherDetailsP = $this->clean($vals[75]);

      $s5_buy = $this->clean($vals[76]);
      $s5_sell = $this->clean($vals[77]);
      $s5_consultancies = $this->clean($vals[78]);
      $s5_primaryplacements = $this->clean($vals[79]);
      $s5_portfoliomanagement = $this->clean($vals[80]);
      $s5_custody = $this->clean($vals[81]);
      $s5_mutualmoney = $this->clean($vals[82]);
      $s5_loan = $this->clean($vals[83]);
      $s5_mutualfunds = $this->clean($vals[84]);
      $s5_investmentfunds = $this->clean($vals[85]);
      $s5_derivatives = $this->clean($vals[86]);
      $s5_collectivefunds = $this->clean($vals[87]);
      $s5_otherQ = $this->clean($vals[88]);
      $s5_otherDetailsQ = $this->clean($vals[89]);

      $s5_account1 = $this->clean($vals[90]);
      $s5_account2 = $this->clean($vals[91]);
      $s5_account3 = $this->clean($vals[92]);

      $operations[] = [
        "userid" => $userId,
        "fechacreacion" => date("Y-m-d h:i:s"),
        "estado" => 1,

        "opempresa_uno" => $s1_company,
        "opnroregistro_uno" => $s1_code,
        "opoficina_uno" => $s1_office,
        "opfecharegistro_uno" => $s1_registeredAt,

        "opapellidos_dos" => $s2_lastname,
        "opnombres_dos" => $s2_name,
        "opfechanac_dos" => $s2_birthday,
        "opnacionalidad_dos" => $s2_nationality,
        "opprofesion_dos" => $s2_ocupation,
        "opdomicilio_dos" => $s2_address,
        "opcodigopostal_dos" => $s2_postalcode,
        "opprovincia_dos" => $s2_province,
        "opdepartamento_dos" => $s2_department,
        "oppais_dos" => $s2_country,
        "optelefono_dos" => $s2_cellphone,
        "opdni_dos" => $s2_dni,
        "oprut_dos" => $s2_rut,
        "oplm_dos" => $s2_lm,
        "opci_dos" => $s2_ci,
        "opce_dos" => $s2_ce,
        "oppasaporte_dos" => $s2_passport,
        "opermitido_dos" => $s2_emittedAt,
        "opruc_dos" => $s2_ruc,
        "opotro_dos" => $s2_other,

        "opapellidos_tres" => $s3_lastname,
        "opnombres_tres" => $s3_name,
        "opfechanac_tres" => $s3_birthday,
        "opnacionalidad_tres" => $s3_nationality,
        "opprofesion_tres" => $s3_ocupation,
        "opdomicilio_tres" => $s3_address,
        "opcodigopostal_tres" => $s3_postalcode,
        "opprovincia_tres" => $s3_province,
        "opdepartamento_tres" => $s3_department,
        "oppais_tres" => $s3_country,
        "optelefono_tres" => $s3_cellphone,
        "opdni_tres" => $s3_dni,
        "oprut_tres" => $s3_rut,
        "oplm_tres" => $s3_lm,
        "opci_tres" => $s3_ci,
        "opce_tres" => $s3_ce,
        "oppasaporte_tres" => $s3_passport,
        "opermitido_tres" => $s3_emittedAt,
        "opruc_tres" => $s3_ruc,
        "opotro_tres" => $s3_other,

        "opbeneficiario1" => $s4_beneficiary,

        "opapellidos_cuatro" => $s4_lastname,
        "opnombres_cuatro" => $s4_name,
        "opfechanac_cuatro" => $s4_birthday,
        "opnacionalidad_cuatro" => $s4_nationality,
        "opprofesion_cuatro" => $s4_ocupation,
        "opdomicilio_cuatro" => $s4_address,
        "opcodigopostal_cuatro" => $s4_postalcode,
        "opprovincia_cuatro" => $s4_province,
        "opdepartamento_cuatro" => $s4_department,
        "oppais_cuatro" => $s4_country,
        "optelefono_cuatro" => $s4_cellphone,
        "opdni_cuatro" => $s4_dni,
        "oprut_cuatro" => $s4_rut,
        "oplm_cuatro" => $s4_lm,
        "opci_cuatro" => $s4_ci,
        "opce_cuatro" => $s4_ce,
        "oppasaporte_cuatro" => $s4_passport,
        "opermitido_cuatro" => $s4_emittedAt,
        "opruc_cuatro" => $s4_ruc,
        "opotro_cuatro" => $s4_other,

        "opmontooperaciondolares" => $s5_amount,
        "opfechaoperacion" => $s5_date,
        "oplugarrealizacion" => $s5_location,

        "opmonedanacional" => $s5_nationalcurrency,
        "opmonedaextranjera" => $s5_foreigncurrency,
        "opespecificarmoneda" => $s5_foreigncurrencyDetails,
        "opchequegerencia" => $s5_cashierscheck,
        "opchequeviajero" => $s5_travelerscheck,
        "opordenespago" => $s5_paymentorder,
        "opotros_uno" => $s5_otherP,
        "opespecificar_uno" => $s5_otherDetailsP,

        "opcompravalores" => $s5_buy,
        "opventavalores" => $s5_sell,
        "opasesorias" => $s5_consultancies,
        "opcolocacionesprimarias" => $s5_primaryplacements,
        "opadministracioncartera" => $s5_portfoliomanagement,
        "opcustodiavalores" => $s5_custody,
        "opmutuosdinero" => $s5_mutualmoney,
        "opprestamovalores" => $s5_loan,
        "opfondosmutuos" => $s5_mutualfunds,
        "opfondosinversion" => $s5_investmentfunds,
        "opderivados" => $s5_derivatives,
        "opfondoscolectivos" => $s5_collectivefunds,
        "opotros_dos" => $s5_otherQ,
        "opespecificar_dos" => $s5_otherDetailsQ,

        "opcuenta_uno" => $s5_account1,
        "opcuenta_dos" => $s5_account2,
        "opcuenta_tres" => $s5_account3,
      ];

      // Begin - For Benefiaciaries:
      $aux = [];
      $valsBens = [114, 134, 154, 174];
      foreach ($valsBens as $valben) {
        if (count($vals) >= $valben) {
          $index = $valben - 20;

          $isEmpty = true;
          for ($i=$index; $i<$index+20; $i++) {
            if (!empty($vals[$i])) {
              $isEmpty = false;
            }
          }

          if (!$isEmpty) {
            $operationId = 0;            
            $lastname = $this->clean($vals[$index]);
            $name = $this->clean($vals[$index+1]);
            $dni = $this->clean($vals[$index+2]);
            $rut = $this->clean($vals[$index+3]);
            $lm = $this->clean($vals[$index+4]);
            $ci = $this->clean($vals[$index+5]);
            $ce = $this->clean($vals[$index+6]);
            $passport = $this->clean($vals[$index+7]);
            $emittedAt = $this->clean($vals[$index+8]);
            $ruc = $this->clean($vals[$index+9]);
            $other = $this->clean($vals[$index+10]);
            $birthday = $this->clean($vals[$index+11]);
            $nationality = $this->clean($vals[$index+12]);
            $ocupation = $this->clean($vals[$index+13]);
            $address = $this->clean($vals[$index+14]);
            $postalcode = $this->clean($vals[$index+15]);
            $province = $this->clean($vals[$index+16]);
            $department = $this->clean($vals[$index+17]);
            $country = $this->clean($vals[$index+18]);
            $cellphone = $this->clean($vals[$index+19]);

            $beneficiary = [
              "regoperacionid" => 0,
              "beneape" => $lastname,
              "benenom" => $name,
              "benefecha" => $birthday,
              "benenac" => $nationality,
              "beneprofesion" => $ocupation,
              "benecp" => $postalcode,
              "benepais" => $country,
              "benedepto" => $department,
              "beneprov" => $province,
              "benetelf" => $cellphone,
              "benedireccion" => $address,
              "benedni" => $dni,
              "benerut" => $rut,
              "benelm" => $lm,
              "beneci" => $ci,
              "benece" => $ce,
              "benepasaporte" => $passport,
              "beneemitido" => $emittedAt,
              "beneruc" => $ruc,
              "beneotro" => $other,
            ];

            $aux[] = $beneficiary;
          }
        }
      }
      $beneficiaries[] = $aux;
      // End - For Benefiaciaries:
    }

    array_shift($operations);
    array_shift($operations);
    array_shift($operations); // Borrar uno
    Operation::insert($operations);
    $operationsCreated = Operation::select("regoperacionid as id")->orderBy('regoperacionid', 'desc')->limit(count($operations))->get();

    // Beneficiaries
    array_shift($beneficiaries);
    array_shift($beneficiaries);
    array_shift($beneficiaries); // Borrar uno
    $benes = [];
    for ($i=0; $i<count($operationsCreated); $i++) {
      for ($j=0; $j<count($beneficiaries[$i]); $j++) {
        $beneficiaries[$i][$j]["regoperacionid"] = $operationsCreated[$i]->id;
        $benes[] = $beneficiaries[$i][$j];
      }
    }
    OperationBeneficiary::insert($benes);

    // Response:
    // return back()->with("success", "¡Carga Masiva Correcta!");
    return redirect("operations");
  }

  function customDate($date) {
    return date_format(now()->parse($date), 'd-m-Y' );
  }

  public function export($id) {
    /* BEGIN - DB */
    $registro = Operation::find($id);

    $userid               = $registro->userid;
    $opempresa_uno        = $registro->opempresa_uno;
    $opnroregistro_uno    = $registro->opnroregistro_uno;
    $opoficina_uno        = $registro->opoficina_uno;
    $opfecharegistro_uno  = $this->customDate($registro->opfecharegistro_uno);
    $opapellidos_dos      = $registro->opapellidos_dos;
    $opnombres_dos        = $registro->opnombres_dos;
    $optipodocumento_dos  = $registro->optipodocumento_dos;

    $opdni_dos            = $registro->opdni_dos;
    $oprut_dos            = $registro->oprut_dos;
    $oplm_dos             = $registro->oplm_dos;
    $opci_dos             = $registro->opci_dos;
    $opce_dos             = $registro->opce_dos;
    $oppasaporte_dos      = $registro->oppasaporte_dos;
    $opermitido_dos       = $registro->opermitido_dos;
    $opruc_dos            = $registro->opruc_dos;
    $opotro_dos           = $registro->opotro_dos;
    $opfechanac_dos       = $this->customDate($registro->opfechanac_dos);
    $opnacionalidad_dos   = $registro->opnacionalidad_dos;
    $opprofesion_dos      = $registro->opprofesion_dos;
    $opdomicilio_dos      = $registro->opdomicilio_dos;
    $opcodigopostal_dos   = $registro->opcodigopostal_dos;
    $opprovincia_dos      = $registro->opprovincia_dos;
    $opdepartamento_dos   = $registro->opdepartamento_dos;
    $oppais_dos           = $registro->oppais_dos;
    $optelefono_dos       = $registro->optelefono_dos;

    $opapellidos_tres     = $registro->opapellidos_tres;
    $opnombres_tres       = $registro->opnombres_tres;
    $optipodocumento_tres = $registro->optipodocumento_tres;

    $opdni_tres           = $registro->opdni_tres;
    $oprut_tres           = $registro->oprut_tres;
    $oplm_tres            = $registro->oplm_tres;
    $opci_tres            = $registro->opci_tres;
    $opce_tres            = $registro->opce_tres;
    $oppasaporte_tres     = $registro->oppasaporte_tres;
    $opermitido_tres      = $registro->opermitido_tres;
    $opruc_tres           = $registro->opruc_tres;
    $opotro_tres          = $registro->opotro_tres;
    $opfechanac_tres      = $this->customDate($registro->opfechanac_tres);
    $opnacionalidad_tres  = $registro->opnacionalidad_tres;
    $opprofesion_tres     = $registro->opprofesion_tres;
    $opdomicilio_tres     = $registro->opdomicilio_tres;
    $opcodigopostal_tres  = $registro->opcodigopostal_tres;
    $opprovincia_tres     = $registro->opprovincia_tres;
    $opdepartamento_tres  = $registro->opdepartamento_tres;
    $oppais_tres          = $registro->oppais_tres;
    $optelefono_tres      = $registro->optelefono_tres;

    $opbeneficiario1        = $registro->opbeneficiario1;
    $opapellidos_cuatro     = $registro->opapellidos_cuatro;
    $opnombres_cuatro       = $registro->opnombres_cuatro;
    $optipodocumento_cuatro = $registro->optipodocumento_cuatro;

    $opdni_cuatro           = $registro->opdni_cuatro;
    $oprut_cuatro           = $registro->oprut_cuatro;
    $oplm_cuatro            = $registro->oplm_cuatro;
    $opci_cuatro            = $registro->opci_cuatro;
    $opce_cuatro            = $registro->opce_cuatro;
    $oppasaporte_cuatro     = $registro->oppasaporte_cuatro;
    $opermitido_cuatro      = $registro->opermitido_cuatro;
    $opruc_cuatro           = $registro->opruc_cuatro;
    $opotro_cuatro          = $registro->opotro_cuatro;
    $opfechanac_cuatro      = $this->customDate($registro->opfechanac_cuatro);
    $opnacionalidad_cuatro  = $registro->opnacionalidad_cuatro;
    $opprofesion_cuatro     = $registro->opprofesion_cuatro;
    $opdomicilio_cuatro     = $registro->opdomicilio_cuatro;
    $opcodigopostal_cuatro  = $registro->opcodigopostal_cuatro;
    $opprovincia_cuatro     = $registro->opprovincia_cuatro;
    $opdepartamento_cuatro  = $registro->opdepartamento_cuatro;
    $oppais_cuatro          = $registro->oppais_cuatro;
    $optelefono_cuatro      = $registro->optelefono_cuatro;

    $opmontooperaciondolares  = $registro->opmontooperaciondolares;
    $opfechaoperacion         = $this->customDate($registro->opfechaoperacion);
    $oplugarrealizacion       = $registro->oplugarrealizacion;
    $opmonedanacional         = $registro->opmonedanacional;
    $opmonedaextranjera       = $registro->opmonedaextranjera;
    $opespecificarmoneda      = $registro->opespecificarmoneda;
    $opchequegerencia         = $registro->opchequegerencia;
    $opchequeviajero          = $registro->opchequeviajero;
    $opordenespago            = $registro->opordenespago;
    $opotros_uno              = $registro->opotros_uno;
    $opespecificar_uno        = $registro->opespecificar_uno;
    $opcompravalores          = $registro->opcompravalores;
    $opventavalores           = $registro->opventavalores;
    $opasesorias              = $registro->opasesorias;
    $opcolocacionesprimarias  = $registro->opcolocacionesprimarias;
    $opadministracioncartera  = $registro->opadministracioncartera;
    $opcustodiavalores        = $registro->opcustodiavalores;
    $opmutuosdinero           = $registro->opmutuosdinero;
    $opprestamovalores        = $registro->opprestamovalores;
    $opfondosmutuos           = $registro->opfondosmutuos;

    $opfondosinversion        = $registro->opfondosinversion;
    $opderivados              = $registro->opderivados;
    $opfondoscolectivos       = $registro->opfondoscolectivos;

    $opotros_dos              = $registro->opotros_dos;
    $opespecificar_dos        = $registro->opespecificar_dos;
    $opcuenta_uno             = $registro->opcuenta_uno;
    $opcuenta_dos             = $registro->opcuenta_dos;
    $opcuenta_tres            = $registro->opcuenta_tres;
    $opcuenta_cuatro          = $registro->opcuenta_cuatro;

    $regbeneficiarios = OperationBeneficiary::where('regoperacionid', '=', $id)->get();
    $v = 0;
    $array = [];
    foreach ($regbeneficiarios as $archivo) {
      $beneopid       = $archivo->beneopid;
      $regoperacionid = $archivo->regoperacionid;
      $beneape        = $archivo->beneape;
      $benenom        = $archivo->benenom;
      $benefecha      = $this->customDate($archivo->benefecha);
      $benenac        = $archivo->benenac;
      $beneprofesion  = $archivo->beneprofesion;
      $benecp         = $archivo->benecp;
      $benepais       = $archivo->benepais;
      $benedepto      = $archivo->benedepto;
      $beneprov       = $archivo->beneprov;
      $benetelf       = $archivo->benetelf;
      $benedireccion  = $archivo->benedireccion;
      $benedni        = $archivo->benedni;
      $benerut        = $archivo->benerut;
      $benelm         = $archivo->benelm;
      $beneci         = $archivo->beneci;
      $benece         = $archivo->benece;
      $benepasaporte  = $archivo->benepasaporte;
      $beneemitido    = $archivo->beneemitido;
      $beneruc        = $archivo->beneruc;
      $beneotro       = $archivo->beneotro;
  
      $array [] = array("beneopid" => $beneopid,"beneape"=> $beneape,"benenom"=> $benenom,"benefecha"=> $benefecha,"benenac"=> $benenac,"beneprofesion"=> $beneprofesion,"benecp"=> $benecp,"benepais"=> $benepais,"benedepto"=> $benedepto,"beneprov"=> $beneprov,"benetelf"=> $benetelf,"benedireccion"=> $benedireccion,"benedni"=> $benedni,"benerut"=> $benerut,"benelm"=> $benelm,"beneci"=> $beneci,"benece"=> $benece,"benepasaporte"=> $benepasaporte,"beneemitido"=> $beneemitido,"beneruc"=> $beneruc,"beneotro"=> $beneotro );
  
      $array_excel []= array("hbeneape" => strval(78+$v),"hbenenom"=> strval(78+$v),"hbenefecha"=> strval(81+$v),"hbenenac"=> strval(81+$v),"hbeneprofesion"=> strval(81+$v),"hbenecp"=> strval(83+$v),"hbenepais"=> strval(85+$v),"hbenedepto"=> strval(85+$v),"hbeneprov"=> strval(85+$v),"hbenetelf"=> strval(85+$v),"hbenedireccion"=> strval(83+$v),"hbenedni"=> strval(78+$v),"hbenerut"=> strval(78+$v),"hbenelm"=> strval(79+$v),"hbeneci"=> strval(80+$v),"hbenece"=> strval(81+$v),"hbenepasaporte"=> strval(82+$v),"hbeneemitido"=> strval(82+$v),"hbeneruc"=> strval(83+$v),"hbeneotro"=> strval(83+$v) );
      $v = $v+10;
    }
    /* END - DB */

    /* BEGIN - FILE */
    // echo getcwd();die;
    $template = "storage/app/public/templates/Plantilla_Registro_de_Operaciones.xlsx";
    $generatedAt = date("Ymd");
    $filename = "Registro_de_operaciones___{$generatedAt}___{$id}.xlsx";

    $fileType = PHPExcel_IOFactory::identify($template);
    $objReader = PHPExcel_IOFactory::createReader($fileType);
    $objPHPExcel = $objReader->load($template);

    /* Begin - Formatting */
    $value = "1. Empresa: ".$opempresa_uno;
    $hoja = 'B4';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = "2. Nº del registro: ".$opnroregistro_uno;
    $hoja = 'G4';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = "3. Oficina: ".$opoficina_uno;
    $hoja = 'B5';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = "4. Fecha del registro (dd/mm/aaaa): ".$opfecharegistro_uno;
    $hoja = 'G5';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opapellidos_dos;
    $hoja = 'B8';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opnombres_dos;
    $hoja = 'E8';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opfechanac_dos;
    $hoja = 'B11';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opnacionalidad_dos;
    $hoja = 'c10';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opprofesion_dos;
    $hoja = 'E10';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opdomicilio_dos;
    $hoja = 'B13';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opcodigopostal_dos;
    $hoja = 'F13';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opprovincia_dos;
    $hoja = 'B15';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opdepartamento_dos;
    $hoja = 'F15';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $oppais_dos;
    $hoja = 'H15';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $optelefono_dos;
    $hoja = 'J15';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opruc_dos;
    $hoja = 'I12';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $optipodocumento_dos;
    /*$hoja = 'H8';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);*/

    $value = $oprut_dos;
    $hoja = 'K8';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $oplm_dos;
    $hoja = 'I9';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opci_dos;
    $hoja = 'K9';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opce_dos;
    $hoja = 'I10';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $oppasaporte_dos;
    $hoja = 'I11';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opermitido_dos;
    $hoja = 'K11';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opdni_dos;
    $hoja = 'I8';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opotro_dos;
    $hoja = 'K12';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opapellidos_tres;
    $hoja = 'B18';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opnombres_tres;
    $hoja = 'E18';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opfechanac_tres;
    $hoja = 'B21';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opnacionalidad_tres;
    $hoja = 'c20';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opprofesion_tres;
    $hoja = 'E20';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opdomicilio_tres;
    $hoja = 'B23';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opcodigopostal_tres;
    $hoja = 'F23';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opprovincia_tres;
    $hoja = 'B25';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opdepartamento_tres;
    $hoja = 'F25';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $oppais_tres;
    $hoja = 'H25';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $optelefono_tres;
    $hoja = 'J25';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opruc_tres;
    $hoja = 'I22';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opruc_cuatro;
    $hoja = 'I35';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $optipodocumento_dos;
    /*$hoja = 'H18';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);*/

    $value = $oprut_tres;
    $hoja = 'K18';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $oplm_tres;
    $hoja = 'I19';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opci_tres;
    $hoja = 'K19';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opce_tres;
    $hoja = 'I20';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $oppasaporte_tres;
    $hoja = 'I21';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opermitido_tres;
    $hoja = 'K21';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opdni_tres;
    $hoja = 'I18';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opotro_tres;
    $hoja = 'K22';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opbeneficiario1;
    $hoja = 'H28';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opapellidos_cuatro;
    $hoja = 'B31';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opnombres_cuatro;
    $hoja = 'E31';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opfechanac_cuatro;
    $hoja = 'B34';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opnacionalidad_cuatro;
    $hoja = 'c34';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opprofesion_cuatro;
    $hoja = 'E34';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opdomicilio_cuatro;
    $hoja = 'B36';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opcodigopostal_cuatro;
    $hoja = 'F36';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opprovincia_cuatro;
    $hoja = 'B38';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opdepartamento_cuatro;
    $hoja = 'F38';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $oppais_cuatro;
    $hoja = 'H38';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $optelefono_cuatro;
    $hoja = 'J38';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $optipodocumento_cuatro;
    /*$hoja = 'H31';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);*/

    $value = $oprut_cuatro;
    $hoja = 'K31';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $oplm_cuatro;
    $hoja = 'I32';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opci_cuatro;
    $hoja = 'K32';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opce_cuatro;
    $hoja = 'I33';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $oppasaporte_cuatro;
    $hoja = 'I34';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opermitido_cuatro;
    $hoja = 'K34';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opdni_cuatro;
    $hoja = 'I31';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opotro_cuatro;
    $hoja = 'K35';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = "42. Monto de la operación (US$) \n".$opmontooperaciondolares;
    $hoja = 'B40';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = "43. Fecha de la operación (dd/mm/aaaa)\n ".$opfechaoperacion;
    $hoja = 'D40';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = "44. Lugar de realización \n".$oplugarrealizacion;
    $hoja = 'H40';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opmonedanacional;
    $hoja = 'C42';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = empty($opmonedaextranjera) ? '' : 'Sí';
    $hoja = 'C44';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opespecificarmoneda;
    $hoja = 'G44';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opchequegerencia;
    $hoja = 'C46';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opchequeviajero;
    $hoja = 'C48';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opordenespago;
    $hoja = 'C50';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opotros_uno;
    $hoja = 'C52';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opespecificar_uno;
    $hoja = 'G52';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opcompravalores;
    $hoja = 'C55';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opventavalores;
    $hoja = 'C57';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opasesorias;
    $hoja = 'C59';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opcolocacionesprimarias;
    $hoja = 'C61';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opadministracioncartera;
    $hoja = 'C63';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opcustodiavalores;
    $hoja = 'C65';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opmutuosdinero;
    $hoja = 'G55';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opprestamovalores;
    $hoja = 'G57';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opfondosmutuos;
    $hoja = 'G59';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opfondosinversion;
    $hoja = 'G61';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opderivados;
    $hoja = 'G63';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opfondoscolectivos;
    $hoja = 'G65';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opotros_dos;
    $hoja = 'J55';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opespecificar_dos;//"Especificar  ".
    $hoja = 'J59';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opcuenta_uno;
    $hoja = 'C70';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opcuenta_dos;
    $hoja = 'G70';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opcuenta_tres;
    $hoja = 'C72';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    $value = $opcuenta_cuatro;
    $hoja = 'G72';
    $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
    $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);

    //Beneficiarios
    for ($i=0; $i<count($array); $i++) { 
      $value = strval($array[$i]['beneape']);
      $hoja = strval("B".$array_excel[$i]['hbeneape']);
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benenom'];
      $hoja = 'E'.$array_excel[$i]['hbenenom'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benefecha'];
      $hoja = 'B'.$array_excel[$i]['hbenefecha'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benenac'];
      $hoja = 'C'.$array_excel[$i]['hbenenac'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['beneprofesion'];
      $hoja = 'E'.$array_excel[$i]['hbeneprofesion'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benedireccion'];
      $hoja = 'B'.$array_excel[$i]['hbenedireccion'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benecp'];
      $hoja = 'F'.$array_excel[$i]['hbenecp'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['beneprov'];
      $hoja = 'B'.$array_excel[$i]['hbeneprov'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benedepto'];
      $hoja = 'F'.$array_excel[$i]['hbenedepto'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benepais'];
      $hoja = 'H'.$array_excel[$i]['hbenepais'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benetelf'];
      $hoja = 'J'.$array_excel[$i]['hbenetelf'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benedni'];
      $hoja = 'I'.$array_excel[$i]['hbenedni'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benerut'];
      $hoja = 'K'.$array_excel[$i]['hbenerut'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benelm'];
      $hoja = 'I'.$array_excel[$i]['hbenelm'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['beneci'];
      $hoja = 'I'.$array_excel[$i]['hbeneci'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benece'];
      $hoja = 'I'.$array_excel[$i]['hbenece'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['benepasaporte'];
      $hoja = 'I'.$array_excel[$i]['hbenepasaporte'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['beneemitido'];
      $hoja = 'K'.$array_excel[$i]['hbeneemitido'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['beneruc'];
      $hoja = 'I'.$array_excel[$i]['hbeneruc'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
      $value = $array[$i]['beneotro'];
      $hoja = 'K'.$array_excel[$i]['hbeneotro'];
      $objPHPExcel->getActiveSheet()->setCellValue($hoja, $value);
      $objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle($hoja)->setQuotePrefix(true);
    }
    /* End - Formatting */

    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);

    $objWriter->save("storage/temp/{$filename}");
    /* END - FILE */

    return response()->download("storage/temp/{$filename}");
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
    $operationId = $request->operationId;

    $operation = Operation::find($operationId);
    $operation->userIds = $this->str2lst($operation->userIds);

    $userIds = $operation->userIds;
    if (in_array($userId, $userIds)) {
      return $operation;
    }
    $userIds[] = strval($userId);
    $operation->userIds = $userIds;
    $operation->save();

    return $operation;
  }
}
