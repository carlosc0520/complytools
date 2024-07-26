<?php

namespace App\Http\Controllers;

use App\Models\BusinessSector;
use App\Models\BusinessProcess;
use App\Models\Ocupation;
use App\Models\Obligation;
use App\Models\Ciu;
use App\Models\ScStatus;
use App\Models\Composition;
use App\Models\Sensible;
use App\Models\Pep;
use App\Models\Country;
use App\Models\Office;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Funding;
use App\Models\CompanyUser;
use App\Models\CompanyType;
use App\Models\CompanySize;
use App\Models\OperationType;
use App\Models\OperationUrgency;
use App\Models\OperationSignalType;
use App\Models\OperationSignal;
use App\Models\OperationDepartment;
use App\Models\OperationProvince;
use App\Models\OperationDistrict;
use App\Models\OperationCrime;
use App\Models\OperationCrimeType;
use App\Models\OperationActivity;
use App\Models\OperationCurrency;
use App\Models\OperationPeopleType;
use App\Models\OperationCondition;
use App\Models\OperationNationality;
use App\Models\OperationDocumentType;
use App\Models\OperationOcupation;

use Illuminate\Http\Request;

class CommonController extends Controller {
  public function listAreasByUser($userId) {
    /* Begin - List Areas By Company */
    $user = CompanyUser::where("wp_users_id", "=", $userId)->first();
    $users = CompanyUser::where("tbd_empresa_id", "=", $user->tbd_empresa_id)->get();
    $userIds = [];
    foreach ($users as $usr) {
      $userIds[] = $usr->wp_users_id;
    }
    /* End - List Areas By Company */

    $data = BusinessSector::select(
      // "tb_usuario_area.usuario_area_id as id",
	    // "tb_usuario_area.usuario_id as userId",
      "tb_usuario_area.area_id as areaId",
      "tb_division_area.nombre as area"
    )
    ->join('tb_division_area', 'tb_division_area.division_area_id', '=', 'tb_usuario_area.area_id')
    ->whereIn('tb_usuario_area.usuario_id', $userIds)
    ->orderBy('tb_division_area.nombre', 'asc')
    ->get();

    $areas = [];
    foreach ($data as $ar) { // [{ id: 1, name: ale }, { id: 2, name: hen }]
      if (!in_array($ar, $areas)) {
        $areas[] = $ar;
      }
    }
    return $areas;
  }

  public function listProcessesByArea($areaId) {
    $data = BusinessProcess::select(
      "tb_area_proceso.area_proceso_id as id",
      "tb_area_proceso.area_id as areaId",
      "tb_area_proceso.proceso_id as processId",
      "tb_proceso.nombre as process"
    )
    ->join('tb_proceso', 'tb_proceso.proceso_id', '=', 'tb_area_proceso.proceso_id')
    ->where('tb_area_proceso.area_id', '=', $areaId)
    ->orderBy('tb_proceso.nombre', 'asc')
    ->get();

    return $data;
  }

  public function listOcupations() {
    $data = Ocupation::select(
      "ocupacionid AS id",
      "ocupaciondescrip AS name",
      "ocupacionriesgo AS risk_val",
      "ocupacionpeso AS weight",
    )
    ->orderBy('ocupaciondescrip', 'asc')
    ->get();

    return $data;
  }

  public function listObligations() {
    $data = Obligation::select(
      "obligadoid AS id",
      "obligadodescrip AS name",
      "obligadoriesgo AS risk_val",
      "obligadopeso AS weight",
    )
    ->orderBy('obligadodescrip', 'asc')
    ->get();

    return $data;
  }

  public function listCius() {
    $data = Ciu::select(
      "ciuid AS id",
      "ciucodigo AS code",
      "ciudescrip AS name",
      "ciuriesgo AS risk_val",
      "ciupesopn AS weight_dni",
      "ciupesopj AS weight_ruc",
    )
    ->orderBy('ciudescrip', 'asc')
    ->get();

    return $data;
  }

  public function listScStatus() {
    $data = ScStatus::select(
      "estadoid AS id",
      "estadodescrip AS name",
      "estadoriesgo AS risk_val",
      "estadopeso AS weight",
    )
    ->orderBy('estadodescrip', 'asc')
    ->get();

    return $data;
  }

  public function listCompositions() {
    $data = Composition::select(
      "composicionid AS id",
      "composiciondescrip AS name",
      "composicionriesgo AS risk_val",
      "composicionpeso AS weight",
    )
    ->orderBy('composiciondescrip', 'asc')
    ->get();

    return $data;
  }

  public function listSensibles() {
    $data = Sensible::select(
      "sensibleid AS id",
      "sensibledescrip AS name",
      "sensibleriesgo AS risk_val",
      "sensiblepeso AS weight",
    )
    ->orderBy('sensibledescrip', 'asc')
    ->get();

    return $data;
  }

  public function listPeps() {
    $data = Pep::select(
      "pepid AS id",
      "pepdescrip AS name",
      "pepriesgo AS risk_val",
      "peppeso AS weight",
    )
    ->orderBy('pepdescrip', 'asc')
    ->get();

    return $data;
  }

  public function listCountries() {
    $data = Country::select(
      "paisid AS id",
      "paisdescrip AS name",
      "paisriesgo AS risk_val",
      "paispesu AS weight",
    )
    ->orderBy('paisdescrip', 'asc')
    ->get();

    return $data;
  }

  public function listOffices() {
    $data = Office::select(
      "oficinaid AS id",
      "oficinadescrip AS name",
      "oficinariesgo AS risk_val",
      "oficinapeso AS weight",
    )
    ->orderBy('oficinadescrip', 'asc')
    ->get();

    return $data;
  }

  public function listProducts() {
    $data = Product::select(
      "productoid AS id",
      "productodescrip AS name",
      "productoriesgo AS risk_val",
      "productopeso AS weight",
    )
    ->orderBy('productodescrip', 'asc')
    ->get();

    return $data;
  }

  public function listCurrencies() {
    $data = Currency::select(
      "monedaid AS id",
      "monedadescrip AS name",
      "monedariesgo AS risk_val",
      "monedapeso AS weight",
    )
    ->orderBy('monedadescrip', 'asc')
    ->get();

    return $data;
  }

  public function listFundings() {
    $data = Funding::select(
      "fondoid AS id",
      "fondodescrip AS name",
      "fondoriesgo AS risk_val",
      "fondopeso AS weight",
    )
    ->orderBy('fondodescrip', 'asc')
    ->get();

    return $data;
  }

  public function listCompanyTypes() {
    $data = CompanyType::select(
      "tipoid AS id",
      "tipodescrip AS name",
      "tiporiesgo AS risk_val",
      "tipopeso AS weight",
    )
    ->orderBy('tipodescrip', 'asc')
    ->get();

    return $data;
  }

  public function listCompanySizes() {
    $data = CompanySize::select(
      "tamanoid AS id",
      "tamanodescrip AS name",
      "tamanoriesgo AS risk_val",
      "tamanopeso AS weight",
    )
    ->orderBy('tamanodescrip', 'asc')
    ->get();

    return $data;
  }

  public function listScoringNaturalCommons() {
    $ocupations = Ocupation::select(
      "ocupacionid AS id",
      "ocupaciondescrip AS name",
      "ocupacionriesgo AS risk_val",
      "ocupacionpeso AS weight",
    )
    ->orderBy('ocupaciondescrip', 'asc')
    ->get();

    $obligations = Obligation::select(
      "obligadoid AS id",
      "obligadodescrip AS name",
      "obligadoriesgo AS risk_val",
      "obligadopeso AS weight",
    )
    ->orderBy('obligadodescrip', 'asc')
    ->get();

    $cius = Ciu::select(
      "ciuid AS id",
      "ciucodigo AS code",
      "ciudescrip AS name",
      "ciuriesgo AS risk_val",
      "ciupesopn AS weight_dni",
      "ciupesopj AS weight_ruc",
    )
    ->orderBy('ciudescrip', 'asc')
    ->get();

    $scstatus = ScStatus::select(
      "estadoid AS id",
      "estadodescrip AS name",
      "estadoriesgo AS risk_val",
      "estadopeso AS weight",
    )
    ->orderBy('estadodescrip', 'asc')
    ->get();

    $sensibles = Sensible::select(
      "sensibleid AS id",
      "sensibledescrip AS name",
      "sensibleriesgo AS risk_val",
      "sensiblepeso AS weight",
    )
    ->orderBy('sensibledescrip', 'asc')
    ->get();

    $peps = Pep::select(
      "pepid AS id",
      "pepdescrip AS name",
      "pepriesgo AS risk_val",
      "peppeso AS weight",
    )
    ->orderBy('pepdescrip', 'asc')
    ->get();

    $countries = Country::select(
      "paisid AS id",
      "paisdescrip AS name",
      "paisriesgo AS risk_val",
      "paispesu AS weight",
    )
    ->orderBy('paisdescrip', 'asc')
    ->get();

    $offices = Office::select(
      "oficinaid AS id",
      "oficinadescrip AS name",
      "oficinariesgo AS risk_val",
      "oficinapeso AS weight",
    )
    ->orderBy('oficinadescrip', 'asc')
    ->get();

    $products = Product::select(
      "productoid AS id",
      "productodescrip AS name",
      "productoriesgo AS risk_val",
      "productopeso AS weight",
    )
    ->orderBy('productodescrip', 'asc')
    ->get();

    $currencies = Currency::select(
      "monedaid AS id",
      "monedadescrip AS name",
      "monedariesgo AS risk_val",
      "monedapeso AS weight",
    )
    ->orderBy('monedadescrip', 'asc')
    ->get();

    $fundings = Funding::select(
      "fondoid AS id",
      "fondodescrip AS name",
      "fondoriesgo AS risk_val",
      "fondopeso AS weight",
    )
    ->orderBy('fondodescrip', 'asc')
    ->get();

    $data = [
      ["name" => "ocupation", "data" => $ocupations],
      ["name" => "obligation", "data" => $obligations],
      ["name" => "ciu", "data" => $cius],
      ["name" => "scstatus", "data" => $scstatus],
      ["name" => "sensible", "data" => $sensibles],
      ["name" => "pep", "data" => $peps],
      ["name" => "country", "data" => $countries],
      ["name" => "residence", "data" => $countries],
      ["name" => "office", "data" => $offices],
      ["name" => "product", "data" => $products],
      ["name" => "currency", "data" => $currencies],
      ["name" => "funding", "data" => $fundings],
    ];

    return $data;
  }

  public function listScoringCompanyCommons() {
    $ocupations = Ocupation::select(
      "ocupacionid AS id",
      "ocupaciondescrip AS name",
      "ocupacionriesgo AS risk_val",
      "ocupacionpeso AS weight",
    )
    ->orderBy('ocupaciondescrip', 'asc')
    ->get();

    $obligations = Obligation::select(
      "obligadoid AS id",
      "obligadodescrip AS name",
      "obligadoriesgo AS risk_val",
      "obligadopeso AS weight",
    )
    ->orderBy('obligadodescrip', 'asc')
    ->get();

    $cius = Ciu::select(
      "ciuid AS id",
      "ciucodigo AS code",
      "ciudescrip AS name",
      "ciuriesgo AS risk_val",
      "ciupesopn AS weight_dni",
      "ciupesopj AS weight_ruc",
    )
    ->orderBy('ciudescrip', 'asc')
    ->get();

    $compositions = Composition::select(
      "composicionid AS id",
      "composiciondescrip AS name",
      "composicionriesgo AS risk_val",
      "composicionpeso AS weight",
    )
    ->orderBy('composiciondescrip', 'asc')
    ->get();

    $peps = Pep::select(
      "pepid AS id",
      "pepdescrip AS name",
      "pepriesgo AS risk_val",
      "peppeso AS weight",
    )
    ->orderBy('pepdescrip', 'asc')
    ->get();

    $countries = Country::select(
      "paisid AS id",
      "paisdescrip AS name",
      "paisriesgo AS risk_val",
      "paispesu AS weight",
    )
    ->orderBy('paisdescrip', 'asc')
    ->get();

    $offices = Office::select(
      "oficinaid AS id",
      "oficinadescrip AS name",
      "oficinariesgo AS risk_val",
      "oficinapeso AS weight",
    )
    ->orderBy('oficinadescrip', 'asc')
    ->get();

    $products = Product::select(
      "productoid AS id",
      "productodescrip AS name",
      "productoriesgo AS risk_val",
      "productopeso AS weight",
    )
    ->orderBy('productodescrip', 'asc')
    ->get();

    $currencies = Currency::select(
      "monedaid AS id",
      "monedadescrip AS name",
      "monedariesgo AS risk_val",
      "monedapeso AS weight",
    )
    ->orderBy('monedadescrip', 'asc')
    ->get();

    $fundings = Funding::select(
      "fondoid AS id",
      "fondodescrip AS name",
      "fondoriesgo AS risk_val",
      "fondopeso AS weight",
    )
    ->orderBy('fondodescrip', 'asc')
    ->get();

    $companytypes = CompanyType::select(
      "tipoid AS id",
      "tipodescrip AS name",
      "tiporiesgo AS risk_val",
      "tipopeso AS weight",
    )
    ->orderBy('tipodescrip', 'asc')
    ->get();

    $companysizes = CompanySize::select(
      "tamanoid AS id",
      "tamanodescrip AS name",
      "tamanoriesgo AS risk_val",
      "tamanopeso AS weight",
    )
    ->orderBy('tamanodescrip', 'asc')
    ->get();

    $data = [
      ["name" => "obligation", "data" => $obligations],
      ["name" => "ciu", "data" => $cius],
      ["name" => "composition", "data" => $compositions],
      ["name" => "pep", "data" => $peps],
      ["name" => "country", "data" => $countries],
      ["name" => "residence", "data" => $countries],
      ["name" => "office", "data" => $offices],
      ["name" => "product", "data" => $products],
      ["name" => "currency", "data" => $currencies],
      ["name" => "funding", "data" => $fundings],
      ["name" => "type", "data" => $companytypes],
      ["name" => "size", "data" => $companysizes],
    ];

    return $data;
  }

  public function listReportCommons() {
    $types = OperationType::select(
      "tipoid AS id",
      "tipodescrip AS name"
    )
    ->orderBy('tipodescrip', 'asc')
    ->get();

    $urgencies = OperationUrgency::select(
      "urgenciaid AS id",
      "urgenciadescrip AS name"
    )
    ->orderBy('urgenciadescrip', 'asc')
    ->get();

    $signaltypes = OperationSignalType::select(
      "senalid AS id",
      "senaldescrip AS name"
    )
    ->orderBy('senaldescrip', 'asc')
    ->get();

    $signals = OperationSignal::select(
      "opsenalesid AS id",
      "tiposujetoid AS typeId",
      "tsenalid AS signalTypeId",
      "opsenalesdescrip AS name"
    )
    ->where('opsenalesdescrip', '!=', "")
    ->orderBy('opsenalesdescrip', 'asc')
    ->get();

    $departments = OperationDepartment::select(
      "id",
      "nombre AS name"
    )
    ->orderBy('nombre', 'asc')
    ->get();

    $provinces = OperationProvince::select(
      "id",
      "nombre AS name",
      "iddepartamento AS departmentId"
    )
    ->orderBy('nombre', 'asc')
    ->get();

    $districts = OperationDistrict::select(
      "id",
      "nombre AS name",
      "idprovincia AS provinceId",
      "iddepartamento AS departmentId"
    )
    ->orderBy('nombre', 'asc')
    ->get();

    $crimes = OperationCrime::select(
      "delitoid AS id",
      "delitodescrip AS name"
    )
    ->orderBy('delitodescrip', 'asc')
    ->get();

    $crimetypes = OperationCrimeType::select(
      "tipodelitoid AS id",
      "tipodelitodescrip AS name"
    )
    ->orderBy('tipodelitodescrip', 'asc')
    ->get();

    $activities = OperationActivity::select(
      "actividadid AS id",
      "actividaddescrip AS name"
    )
    ->orderBy('actividaddescrip', 'asc')
    ->get();

    $currencies = OperationCurrency::select(
      "monedaid AS id",
      "monedadescrip AS name"
    )
    ->orderBy('monedadescrip', 'asc')
    ->get();

    $peopletypes = OperationPeopleType::select(
      "tipopersonaid AS id",
      "tipopersonadescrip AS name"
    )
    ->orderBy('tipopersonadescrip', 'asc')
    ->get();

    $conditions = OperationCondition::select(
      "condid AS id",
      "conddescrip AS name"
    )
    ->orderBy('conddescrip', 'asc')
    ->get();

    $nationalities = OperationNationality::select(
      "nacionalidadid AS id",
      "nacionalidaddescrip AS name"
    )
    ->orderBy('nacionalidaddescrip', 'asc')
    ->get();

    $documenttypes = OperationDocumentType::select(
      "tipodocid AS id",
      "tipodocdescrip AS name"
    )
    ->orderBy('tipodocdescrip', 'asc')
    ->get();

    $ocupations = OperationOcupation::select(
      "profid AS id",
      "profdescrip AS name"
    )
    ->orderBy('profdescrip', 'asc')
    ->get();

    $data = [
      ["name" => "type", "data" => $types],
      ["name" => "urgency", "data" => $urgencies],
      ["name" => "signaltype", "data" => $signaltypes],
      ["name" => "signal", "data" => $signals],
      ["name" => "department", "data" => $departments],
      ["name" => "province", "data" => $provinces],
      ["name" => "district", "data" => $districts],
      ["name" => "crime", "data" => $crimes],
      ["name" => "crimetype", "data" => $crimetypes],
      ["name" => "activity", "data" => $activities],
      ["name" => "currency", "data" => $currencies],
      ["name" => "peopletype__", "data" => $peopletypes],
      ["name" => "condition__", "data" => $conditions],
      ["name" => "nationality__", "data" => $nationalities],
      ["name" => "documenttype__", "data" => $documenttypes],
      ["name" => "ocupation__", "data" => $ocupations],
      ["name" => "country__", "data" => $nationalities],
      ["name" => "department__", "data" => $departments],
      ["name" => "province__", "data" => $provinces],
      ["name" => "district__", "data" => $districts],
    ];

    return $data;
  }
}
