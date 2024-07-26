<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\CommonController;

use App\Models\User;
use App\Models\Scoring;

use Session;
use PDF;
use Rap2hpoutre\FastExcel\FastExcel;

class ScoringController extends Controller {

  public function viewList() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $userId = Session::get("loginId");

    return view("pages.scoring.index", compact('userId'));
  }

  public function viewGenerateNatural() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $userId = Session::get("loginId");

    return view("pages.scoring.generate.natural", compact('userId'));
  }

  public function viewGenerateCompany() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $userId = Session::get("loginId");

    return view("pages.scoring.generate.company", compact('userId'));
  }

  public function viewDetailsNatural($id) {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }
    $userId = Session::get("loginId");

    $scoring = Scoring::select(
      "scoreid              as    id",
      "userid               as    userId",
      "scoretipo            as    scoreType",
      "scorecreacion        as    created_at",
      "nnombre              as    name",
      "ndni                 as    dni",
      "nconnegocio          as    hasCompany",
      "jrazon               as    company",
      "jruc                 as    ruc",
      "scoreciu             as    ciuId",
      "nestado              as    scstatusId",
      "jtipo                as    companyType",
      "scorefecha           as    birthday",
      "nocupacion           as    ocupationId",
      "nsensible            as    sensibleId",
      "spep                 as    pepId",
      "jtamano              as    sizeId",
      "jcomposicion         as    compositionId",
      "scoreobligado        as    obligationId",
      "scoretransaccion     as    transaction",
      "scorenacionalidad    as    countryId",
      "scoreresidencia      as    residenceId",
      "scoreoficina         as    officeId",
      "scoreproducto        as    productId",
      "scoremoneda          as    currencyId",
      "scorefondo           as    fundingId",
      "scoreriesgo          as    risk_val",
      "scoreobserv          as    obs",
      "scorecargamasiva     as    scoreFile",
      "scorearchivocarga    as    file",
      "status"
    )
    ->where('scoreid', '=', $id)
    ->first();

    $scoring->status = (int)$scoring->status;

    return view("pages.scoring.details.natural", compact("userId", "scoring"));
  }

  public function viewDetailsCompany($id) {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }
    $userId = Session::get("loginId");

    $scoring = Scoring::select(
      "scoreid              as    id",
      "userid               as    userId",
      "scoretipo            as    scoreType",
      "scorecreacion        as    created_at",
      "jrazon               as    name",
      "jruc                 as    dni",
      "scoreciu             as    ciuId",
      "jtipo                as    typeId",
      "scorefecha           as    birthday",
      "spep                 as    pepId",
      "jtamano              as    sizeId",
      "jcomposicion         as    compositionId",
      "scoreobligado        as    obligationId",
      "scoretransaccion     as    transaction",
      "scorenacionalidad    as    countryId",
      "scoreresidencia      as    residenceId",
      "scoreoficina         as    officeId",
      "scoreproducto        as    productId",
      "scoremoneda          as    currencyId",
      "scorefondo           as    fundingId",
      "scoreriesgo          as    risk_val",
      "scoreobserv          as    obs",
      "scorecargamasiva     as    scoreFile",
      "scorearchivocarga    as    file",
      "status"
    )
    ->where('scoreid', '=', $id)
    ->first();

    $scoring->status = (int)$scoring->status;

    return view("pages.scoring.details.company", compact("userId", "scoring"));
  }

  public function list($iduser) {
    /*$sql = "SELECT * FROM (
          SELECT userid, COUNT(scoreid) AS y, '0-1.8' label
          FROM tbs_score
          WHERE (scoreriesgo >= 0.00 AND scoreriesgo <= 1.80) AND userid = $iduser
        UNION ALL
          SELECT userid, COUNT(scoreid) AS y, '1.81-2.6' label
          FROM tbs_score
          WHERE (scoreriesgo >= 1.81 AND scoreriesgo <= 2.60) AND userid = $iduser
        UNION ALL 
          SELECT userid, COUNT(scoreid) AS y, '2.61-3.4' label
          FROM tbs_score
          WHERE (scoreriesgo >= 2.61 AND scoreriesgo <= 3.40) AND userid = $iduser
        UNION ALL
          SELECT userid, COUNT(scoreid) AS y, '3.41-4.2' label
          FROM tbs_score
          WHERE (scoreriesgo >= 3.41 AND scoreriesgo <= 4.20) AND userid = $iduser
        UNION ALL
          SELECT userid, COUNT(scoreid) AS y, '4.21-5' label
          FROM tbs_score
          WHERE (scoreriesgo >= 4.21 AND scoreriesgo <= 5.00) AND userid = $iduser
        GROUP BY userid
      ) T ORDER BY 3";
    $res = Scoring::selectRaw($sql)->get();*/
    $scores = Scoring::select("scoreid AS id", "scoreriesgo AS risk_val")
                ->where("userid", "=", $iduser)
                ->orderBy('scoreriesgo', 'asc')
                ->get();
    $items = [
      "min" => [ "y"=> 0, "label" => "0-1.8" ],
      "low" => [ "y"=> 0, "label" => "1.81-2.6" ],
      "mid" => [ "y"=> 0, "label" => "2.61-3.4" ],
      "high" => [ "y"=> 0, "label" => "3.41-4.2" ],
      "hyper" => [ "y"=> 0, "label" => "4.21-5" ],
    ];
    foreach ($scores as $score) {
      $val = $score->risk_val;
      if ($val >= 0 && $val <= 1.8) {
        $items['min']['y'] = $items['min']['y'] + 1;
      }
      if ($val > 1.8 && $val <= 2.6) {
        $items['low']['y'] = $items['low']['y'] + 1;
      }
      if ($val > 2.6 && $val <= 3.4) {
        $items['mid']['y'] = $items['mid']['y'] + 1;
      }
      if ($val > 3.4 && $val <= 4.2) {
        $items['high']['y'] = $items['high']['y'] + 1;
      }
      if ($val > 4.2 && $val <= 5) {
        $items['hyper']['y'] = $items['hyper']['y'] + 1;
      }
    }
    return $items;
  }

  function setColor($val) {
    $color = '';
    if ($val >= 0 && $val <= 1.8) {
      $color = '#469BE7';
    }
    if ($val > 1.8 && $val <= 2.6) {
      $color = '#429A46';
    }
    if ($val > 2.6 && $val <= 3.4) {
      $color = '#FFFD0A';
    }
    if ($val > 3.4 && $val <= 4.2) {
      $color = '#FF8A00';
    }
    if ($val > 4.2 && $val <= 5) {
      $color = '#FF0000';
    }
    return $color;
  }

  function setColorVal($val) {
    $color = '';
    $label = '';
    if ($val >= 0 && $val <= 1.8) {
      $label = 'Mínimo';
      $color = '#469BE7';
    }
    if ($val > 1.8 && $val <= 2.6) {
      $label = 'Leve';
      $color = '#429A46';
    }
    if ($val > 2.6 && $val <= 3.4) {
      $label = 'Moderado';
      $color = '#FFFD0A';
    }
    if ($val > 3.4 && $val <= 4.2) {
      $label = 'Alto';
      $color = '#FF8A00';
    }
    if ($val > 4.2 && $val <= 5) {
      $label = 'Muy Alto';
      $color = '#FF0000';
    }
    $res = [
      "label" => $label,
      "color" => $color,
    ];
    return $res;
  }

  function setState($status) {
    $state = array(
      "1" => "<span>Editando</span>",
      "2" => "<span>Registrado</span>",
    );
    return isset($status) && isset($state[$status])
            ? $state[$status]
            // : 'Unknow';
            : $state['2'];
  }

  public function dtTable(Request $request) {
    $data = Scoring::select(
        "scoreid AS id",
        "scoretipo AS type",
        "nnombre AS name",
        "jrazon AS company",
        "ndni AS dni",
        "jruc AS ruc",
        "scorecreacion AS created_at",
        "scoreriesgo AS risk_res",
        "status AS state"
      )
      ->where('userid', '=', $request->userId)
      ->orWhereJsonContains('userIds', [$request->userId])
      ->orderBy('scoreid', 'desc')
      ->get();
    return datatables()->of($data)
      ->addIndexColumn()
      ->addColumn('fullname', function ($item) {
        if (isset($item->name) && !empty($item->name)) {
          return $item->name;
        }
        return $item->company;
      })
      ->addColumn('identification', function ($item) {
        if (isset($item->dni) && !empty($item->dni)) {
          return $item->dni;
        }
        return $item->ruc;
      })
      ->addColumn('colorRiskRes', function ($item) {
        $color = $this->setColor($item->risk_res);
        $risk_val = $item->risk_res;
        $cell = "<div style='background-color: $color; height: 40px; display: grid; align-items: center;'>
                  <span style='font-weight: 500;'>$risk_val</span>
                </div>";
        return $cell;
      })
      ->addColumn('status', function ($item) {
        return $this->setState($item->state);
      })
      ->addColumn('actions', function ($item) {
        return array(
          "id" => $item->id,
          "status" => $item->state,
          "type" => $item->type,
        );
      })
      ->rawColumns(['colorRiskRes', 'status', 'actions'])
      ->make(true);
  }

  public function detailsPDFNatural($iduser, $id) {
    if (!isset($iduser) && !isset($id)) {
      return false;
    }

    $user = User::where("ID", "=", $iduser)->first();

    $sc = Scoring::select(
        "tbs_score.scoreid              as    id",
        "tbs_score.userid               as    userId",
        "tbs_score.scoretipo            as    scoreType",
        "tbs_score.scorecreacion        as    created_at",
        "tbs_score.nnombre              as    name",
        "tbs_score.ndni                 as    dni",
        "tbs_score.nconnegocio          as    hasCompany",
        "tbs_score.jrazon               as    company",
        "tbs_score.jruc                 as    ruc",
        "tbs_score.scoreciu             as    ciuId",
        "tbs_score.nestado              as    scstatusId",
        "tbs_score.jtipo                as    companyType",
        "tbs_score.scorefecha           as    birthday",
        "tbs_score.nocupacion           as    ocupationId",
        "tbs_score.nsensible            as    sensibleId",
        "tbs_score.spep                 as    pepId",
        "tbs_score.jtamano              as    sizeId",
        "tbs_score.jcomposicion         as    compositionId",
        "tbs_score.scoreobligado        as    obligationId",
        "tbs_score.scoretransaccion     as    transaction",
        "tbs_score.scorenacionalidad    as    countryId",
        "tbs_score.scoreresidencia      as    residenceId",
        "tbs_score.scoreoficina         as    officeId",
        "tbs_score.scoreproducto        as    productId",
        "tbs_score.scoremoneda          as    currencyId",
        "tbs_score.scorefondo           as    fundingId",
        "tbs_score.scoreriesgo          as    risk_val",
        "tbs_score.scoreobserv          as    obs",
        "tbs_score.scorecargamasiva     as    scoreFile",
        "tbs_score.scorearchivocarga    as    file",
        "tbs_score.status",
        "tbs_ciu.ciudescrip             as    ciu",
        "tbs_ciu.ciuriesgo              as    ciu_risk",
        "tbs_ciu.ciupesopn              as    ciu_weight",
        "tbs_estado.estadodescrip       as    scstatus",
        "tbs_estado.estadoriesgo        as    scstatus_risk",
        "tbs_estado.estadopeso          as    scstatus_weight",
        "tbs_ocupacion.ocupaciondescrip as    ocupation",
        "tbs_ocupacion.ocupacionriesgo  as    ocupation_risk",
        "tbs_ocupacion.ocupacionpeso    as    ocupation_weight",
        "tbs_sensible.sensibledescrip   as    sensible",
        "tbs_sensible.sensibleriesgo    as    sensible_risk",
        "tbs_sensible.sensiblepeso      as    sensible_weight",
        "tbs_pep.pepdescrip             as    pep",
        "tbs_pep.pepriesgo              as    pep_risk",
        "tbs_pep.peppeso                as    pep_weight",
        "tbs_obligado.obligadodescrip   as    obligation",
        "tbs_obligado.obligadoriesgo    as    obligation_risk",
        "tbs_obligado.obligadopeso      as    obligation_weight",
        "pa.paisdescrip     as    country",
        "pa.paisriesgo      as    country_risk",
        "pa.paispesu        as    country_weight",
        "pb.paisdescrip     as    residence",
        "pb.paisriesgo      as    residence_risk",
        "pb.paispesu        as    residence_weight",
        "tbs_oficina.oficinadescrip     as    office",
        "tbs_oficina.oficinariesgo      as    office_risk",
        "tbs_oficina.oficinapeso        as    office_weight",
        "tbs_producto.productodescrip   as    product",
        "tbs_producto.productoriesgo    as    product_risk",
        "tbs_producto.productopeso      as    product_weight",
        "tbs_moneda.monedadescrip       as    currency",
        "tbs_moneda.monedariesgo        as    currency_risk",
        "tbs_moneda.monedapeso          as    currency_weight",
        "tbs_fondo.fondodescrip         as    funding",
        "tbs_fondo.fondoriesgo          as    funding_risk",
        "tbs_fondo.fondopeso            as    funding_weight",
      )
      ->leftjoin('tbs_ciu', 'tbs_ciu.ciucodigo', '=', 'tbs_score.scoreciu')
      ->leftjoin('tbs_estado', 'tbs_estado.estadoid', '=', 'tbs_score.nestado')
      ->leftjoin('tbs_ocupacion', 'tbs_ocupacion.ocupacionid', '=', 'tbs_score.nocupacion')
      ->leftjoin('tbs_sensible', 'tbs_sensible.sensibleid', '=', 'tbs_score.nsensible')
      ->leftjoin('tbs_pep', 'tbs_pep.pepid', '=', 'tbs_score.spep')
      ->leftjoin('tbs_obligado', 'tbs_obligado.obligadoid', '=', 'tbs_score.scoreobligado')
      ->leftjoin('tbs_paises as pa', 'pa.paisid', '=', 'tbs_score.scorenacionalidad')
      ->leftjoin('tbs_paises as pb', 'pb.paisid', '=', 'tbs_score.scoreresidencia')
      ->leftjoin('tbs_oficina', 'tbs_oficina.oficinaid', '=', 'tbs_score.scoreoficina')
      ->leftjoin('tbs_producto', 'tbs_producto.productoid', '=', 'tbs_score.scoreproducto')
      ->leftjoin('tbs_moneda', 'tbs_moneda.monedaid', '=', 'tbs_score.scoremoneda')
      ->leftjoin('tbs_fondo', 'tbs_fondo.fondoid', '=', 'tbs_score.scorefondo')
      ->where('tbs_score.scoreid', '=', $id)
      ->first();

    $age = $this->calcAge($sc->birthday);
    $birthday = $this->findAge($age);
    $amount = $this->findAmount($sc->transaction);

    $sc->ciu_imp = $sc->ciu_risk * $sc->ciu_weight;
    $sc->scstatus_imp = $sc->scstatus_risk * $sc->scstatus_weight;
    $sc->ocupation_imp = $sc->ocupation_risk * $sc->ocupation_weight;
    $sc->sensible_imp = $sc->sensible_risk * $sc->sensible_weight;
    $sc->pep_imp = $sc->pep_risk * $sc->pep_weight;
    $sc->obligation_imp = $sc->obligation_risk * $sc->obligation_weight;
    $sc->country_imp = $sc->country_risk * $sc->country_weight;
    $sc->residence_imp = $sc->residence_risk * $sc->residence_weight;
    $sc->office_imp = $sc->office_risk * $sc->office_weight;
    $sc->product_imp = $sc->product_risk * $sc->product_weight;
    $sc->currency_imp = $sc->currency_risk * $sc->currency_weight;
    $sc->funding_imp = $sc->funding_risk * $sc->funding_weight;

    $sc->birthday_risk = $birthday['risk_val'];
    $sc->birthday_weight = '0.05';
    $sc->birthday_imp = $sc->birthday_risk * $sc->birthday_weight;
    $sc->transaction_risk = $amount['risk_val'];
    $sc->transaction_weight = '0.05';
    $sc->transaction_imp = $sc->transaction_risk * $sc->transaction_weight;

    $sc->fullname = isset($sc->name) && !empty($sc->name) ? $sc->name : $sc->company;
    $sc->identification = isset($sc->dni) && !empty($sc->dni) ? $sc->dni : $sc->ruc;

    $labelcolor = $this->setColorVal($sc->risk_val);
    $sc->label = $labelcolor['label'];
    $sc->color = $labelcolor['color'];

    // -- Begin Calculate --
    $client_risk = $sc->ciu_risk + $sc->scstatus_risk + $sc->birthday_risk + $sc->ocupation_risk + $sc->sensible_risk + $sc->pep_risk + $sc->obligation_risk + $sc->transaction_risk;
    $client_imp = $sc->ciu_imp + $sc->scstatus_imp + $sc->birthday_imp + $sc->ocupation_imp + $sc->sensible_imp + $sc->pep_imp + $sc->obligation_imp + $sc->transaction_imp;
    $location_risk = $sc->country_risk + $sc->residence_risk + $sc->office_risk;
    $location_imp = $sc->country_imp + $sc->residence_imp + $sc->office_imp;
    $other_risk = $sc->product_risk + $sc->currency_risk + $sc->funding_risk;
    $other_imp = $sc->product_imp + $sc->currency_imp + $sc->funding_imp;

    $calc = [
      "client_risk" => round($client_risk/7, 2),
      "client_imp" => $client_imp,
      "location_risk" => round($location_risk/3, 2),
      "location_imp" => $location_imp,
      "other_risk" => round($other_risk/3, 2),
      "other_imp" => $other_imp,
    ];
    // -- End Calculate --

    $path = 'pdfs.scoringdetailsnatural';
    $result = array(
      "meta" => [
        "fullname" => isset($user) ? $user->display_name . " " . $user->lastname : '',
        "created_at" => date("d-m-Y h:i:s A"),
      ],
      "data" => $sc,
      "calc" => $calc,
    );
    view()->share($path, $result);

    $pdf = PDF::loadView($path, ["result" => $result]);

    return $pdf->download('Detalle-de-Scoring-de-Riesgo-Persona-Natural.pdf');
  }

  public function detailsPDFCompany($iduser, $id) {
    if (!isset($iduser) && !isset($id)) {
      return false;
    }

    $user = User::where("ID", "=", $iduser)->first();

    $sc = Scoring::select(
        "tbs_score.scoreid              as    id",
        "tbs_score.userid               as    userId",
        "tbs_score.scoretipo            as    scoreType",
        "tbs_score.scorecreacion        as    created_at",
        "tbs_score.jrazon               as    name",
        "tbs_score.jruc                 as    dni",
        "tbs_score.scoreciu             as    ciuId",
        "tbs_score.jtipo                as    typeId",
        "tbs_score.scorefecha           as    birthday",
        "tbs_score.spep                 as    pepId",
        "tbs_score.jtamano              as    sizeId",
        "tbs_score.jcomposicion         as    compositionId",
        "tbs_score.scoreobligado        as    obligationId",
        "tbs_score.scoretransaccion     as    transaction",
        "tbs_score.scorenacionalidad    as    countryId",
        "tbs_score.scoreresidencia      as    residenceId",
        "tbs_score.scoreoficina         as    officeId",
        "tbs_score.scoreproducto        as    productId",
        "tbs_score.scoremoneda          as    currencyId",
        "tbs_score.scorefondo           as    fundingId",
        "tbs_score.scoreriesgo          as    risk_val",
        "tbs_score.scoreobserv          as    obs",
        "tbs_score.scorecargamasiva     as    scoreFile",
        "tbs_score.scorearchivocarga    as    file",
        "tbs_score.status",
        "tbs_ciu.ciudescrip             as    ciu",
        "tbs_ciu.ciuriesgo              as    ciu_risk",
        "tbs_ciu.ciupesopn              as    ciu_weight",
        "tbs_tipo.tipodescrip           as    type",
        "tbs_tipo.tiporiesgo            as    type_risk",
        "tbs_tipo.tipopeso              as    type_weight",
        "tbs_tamano.tamanodescrip       as    size",
        "tbs_tamano.tamanoriesgo        as    size_risk",
        "tbs_tamano.tamanopeso          as    size_weight",
        "tbs_composicion.composiciondescrip   as    composition",
        "tbs_composicion.composicionriesgo    as    composition_risk",
        "tbs_composicion.composicionpeso      as    composition_weight",
        "tbs_pep.pepdescrip             as    pep",
        "tbs_pep.pepriesgo              as    pep_risk",
        "tbs_pep.peppeso                as    pep_weight",
        "tbs_obligado.obligadodescrip   as    obligation",
        "tbs_obligado.obligadoriesgo    as    obligation_risk",
        "tbs_obligado.obligadopeso      as    obligation_weight",
        "pa.paisdescrip     as    country",
        "pa.paisriesgo      as    country_risk",
        "pa.paispesu        as    country_weight",
        "pb.paisdescrip     as    residence",
        "pb.paisriesgo      as    residence_risk",
        "pb.paispesu        as    residence_weight",
        "tbs_oficina.oficinadescrip     as    office",
        "tbs_oficina.oficinariesgo      as    office_risk",
        "tbs_oficina.oficinapeso        as    office_weight",
        "tbs_producto.productodescrip   as    product",
        "tbs_producto.productoriesgo    as    product_risk",
        "tbs_producto.productopeso      as    product_weight",
        "tbs_moneda.monedadescrip       as    currency",
        "tbs_moneda.monedariesgo        as    currency_risk",
        "tbs_moneda.monedapeso          as    currency_weight",
        "tbs_fondo.fondodescrip         as    funding",
        "tbs_fondo.fondoriesgo          as    funding_risk",
        "tbs_fondo.fondopeso            as    funding_weight",
      )
      ->leftjoin('tbs_ciu', 'tbs_ciu.ciucodigo', '=', 'tbs_score.scoreciu')
      ->leftjoin('tbs_tipo', 'tbs_tipo.tipoid', '=', 'tbs_score.jtipo')
      ->leftjoin('tbs_tamano', 'tbs_tamano.tamanoid', '=', 'tbs_score.jtamano')
      ->leftjoin('tbs_composicion', 'tbs_composicion.composicionid', '=', 'tbs_score.jcomposicion')
      ->leftjoin('tbs_pep', 'tbs_pep.pepid', '=', 'tbs_score.spep')
      ->leftjoin('tbs_obligado', 'tbs_obligado.obligadoid', '=', 'tbs_score.scoreobligado')
      ->leftjoin('tbs_paises as pa', 'pa.paisid', '=', 'tbs_score.scorenacionalidad')
      ->leftjoin('tbs_paises as pb', 'pb.paisid', '=', 'tbs_score.scoreresidencia')
      ->leftjoin('tbs_oficina', 'tbs_oficina.oficinaid', '=', 'tbs_score.scoreoficina')
      ->leftjoin('tbs_producto', 'tbs_producto.productoid', '=', 'tbs_score.scoreproducto')
      ->leftjoin('tbs_moneda', 'tbs_moneda.monedaid', '=', 'tbs_score.scoremoneda')
      ->leftjoin('tbs_fondo', 'tbs_fondo.fondoid', '=', 'tbs_score.scorefondo')
      ->where('tbs_score.scoreid', '=', $id)
      ->first();

    $age = $this->calcAge($sc->birthday);
    $birthday = $this->findAgeC($age);
    $amount = $this->findAmountC($sc->transaction);

    $sc->ciu_imp = $sc->ciu_risk * $sc->ciu_weight;
    $sc->type_imp = $sc->type_risk * $sc->type_weight;
    $sc->size_imp = $sc->size_risk * $sc->size_weight;
    $sc->composition_imp = $sc->composition_risk * $sc->composition_weight;
    $sc->pep_imp = $sc->pep_risk * $sc->pep_weight;
    $sc->obligation_imp = $sc->obligation_risk * $sc->obligation_weight;
    $sc->country_imp = $sc->country_risk * $sc->country_weight;
    $sc->residence_imp = $sc->residence_risk * $sc->residence_weight;
    $sc->office_imp = $sc->office_risk * $sc->office_weight;
    $sc->product_imp = $sc->product_risk * $sc->product_weight;
    $sc->currency_imp = $sc->currency_risk * $sc->currency_weight;
    $sc->funding_imp = $sc->funding_risk * $sc->funding_weight;

    $sc->birthday_risk = $birthday['risk_val'];
    $sc->birthday_weight = '0.05';
    $sc->birthday_imp = $sc->birthday_risk * $sc->birthday_weight;
    $sc->transaction_risk = $amount['risk_val'];
    $sc->transaction_weight = '0.05';
    $sc->transaction_imp = $sc->transaction_risk * $sc->transaction_weight;

    $sc->fullname = isset($sc->name) && !empty($sc->name) ? $sc->name : $sc->company;
    $sc->identification = isset($sc->dni) && !empty($sc->dni) ? $sc->dni : $sc->ruc;

    $labelcolor = $this->setColorVal($sc->risk_val);
    $sc->label = $labelcolor['label'];
    $sc->color = $labelcolor['color'];

    // -- Begin Calculate --
    $client_risk = $sc->ciu_risk + $sc->type_risk + $sc->birthday_risk + $sc->size_risk + $sc->composition_risk + $sc->pep_risk + $sc->obligation_risk + $sc->transaction_risk;
    $client_imp = $sc->ciu_imp + $sc->type_imp + $sc->birthday_imp + $sc->size_imp + $sc->composition_imp + $sc->pep_imp + $sc->obligation_imp + $sc->transaction_imp;
    $location_risk = $sc->country_risk + $sc->residence_risk + $sc->office_risk;
    $location_imp = $sc->country_imp + $sc->residence_imp + $sc->office_imp;
    $other_risk = $sc->product_risk + $sc->currency_risk + $sc->funding_risk;
    $other_imp = $sc->product_imp + $sc->currency_imp + $sc->funding_imp;

    $calc = [
      "client_risk" => round($client_risk/7, 2),
      "client_imp" => $client_imp,
      "location_risk" => round($location_risk/3, 2),
      "location_imp" => $location_imp,
      "other_risk" => round($other_risk/3, 2),
      "other_imp" => $other_imp,
    ];
    // -- End Calculate --

    $path = 'pdfs.scoringdetailscompany';
    $result = array(
      "meta" => [
        "fullname" => isset($user) ? $user->display_name . " " . $user->lastname : '',
        "created_at" => date("d-m-Y h:i:s A"),
      ],
      "data" => $sc,
      "calc" => $calc,
    );
    view()->share($path, $result);

    $pdf = PDF::loadView($path, ["result" => $result]);

    return $pdf->download('Detalle-de-Scoring-de-Riesgo-Persona-Jurídica.pdf');
  }

  public function createNatural(Request $request) {
    /*$isValid = TRUE;
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
          'message' => __("validation.custom.scoring-creation-failed.empty") . ": "  . $keyEmpty,
        ], 500);
      }
    }*/

    $scoring = new Scoring();
    $scoring->userid            = $request->userId;
    $scoring->scoretipo         = 'N'; // $request->scoreType; // Natural [N] | Jurídica [J]
    $scoring->scorecreacion     = date("Y-m-d h:i:s");
    $scoring->nnombre           = $request->name;
    $scoring->ndni              = $request->dni;
    $scoring->nconnegocio       = $request->hasCompany;
    // $scoring->jrazon            = $request->company;
    // $scoring->jruc              = $request->ruc;
    $scoring->scoreciu          = $request->ciuId;          // <-- join
    $scoring->nestado           = $request->scstatusId;  // <-- join
    // $scoring->jtipo             = $request->companyTypeId;
    $scoring->scorefecha        = $request->birthday;
    $scoring->nocupacion        = $request->ocupationId;
    $scoring->nsensible         = $request->sensibleId;     // <-- join
    $scoring->spep              = $request->pepId;          // <-- join
    // $scoring->jtamano           = $request->companySizeId;
    // $scoring->jcomposicion      = $request->compositionId;
    $scoring->scoreobligado     = $request->obligationId;
    $scoring->scoretransaccion  = $request->transaction;
    $scoring->scorenacionalidad = $request->countryId;
    $scoring->scoreresidencia   = $request->residenceId;
    $scoring->scoreoficina      = $request->officeId;
    $scoring->scoreproducto     = $request->productId;
    $scoring->scoremoneda       = $request->currencyId;
    $scoring->scorefondo        = $request->fundingId;
    $scoring->scoreriesgo       = $request->risk_val;
    $scoring->scoreobserv       = $request->obs;            // <-- ¿ OPTIONAL ?
    /*$scoring->scorecargamasiva  = $request->scoreFile;
    $scoring->scorearchivocarga = $request->file;*/
    $scoring->status            = $request->type === 'register' ? 2 : 1;

    $scoring->save();

    return $scoring;
  }

  public function createCompany(Request $request) {
    $scoring = new Scoring();
    $scoring->userid            = $request->userId;
    $scoring->scoretipo         = 'J'; // $request->scoreType; // Natural [N] | Jurídica [J]
    $scoring->scorecreacion     = date("Y-m-d h:i:s");
    $scoring->jrazon            = $request->name;
    $scoring->jruc              = $request->dni;
    $scoring->scoreciu          = $request->ciuId;          // <-- join
    $scoring->jtipo             = $request->typeId;
    $scoring->scorefecha        = $request->birthday;
    $scoring->spep              = $request->pepId;          // <-- join
    $scoring->jtamano           = $request->sizeId;
    $scoring->jcomposicion      = $request->compositionId;
    $scoring->scoreobligado     = $request->obligationId;
    $scoring->scoretransaccion  = $request->transaction;
    $scoring->scorenacionalidad = $request->countryId;
    $scoring->scoreresidencia   = $request->residenceId;
    $scoring->scoreoficina      = $request->officeId;
    $scoring->scoreproducto     = $request->productId;
    $scoring->scoremoneda       = $request->currencyId;
    $scoring->scorefondo        = $request->fundingId;
    $scoring->scoreriesgo       = $request->risk_val;
    $scoring->scoreobserv       = $request->obs;            // <-- ¿ OPTIONAL ?
    /*$scoring->scorecargamasiva  = $request->scoreFile;
    $scoring->scorearchivocarga = $request->file;*/
    $scoring->status            = $request->type === 'register' ? 2 : 1;

    $scoring->save();

    return $scoring;
  }

  public function modifyNatural(Request $request) {
    /*$isValid = TRUE;
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
    }*/

    $scoring = Scoring::find($request->idScoring);
    $scoring->nnombre           = $request->name;
    $scoring->ndni              = $request->dni;
    $scoring->nconnegocio       = $request->hasCompany;
    // $scoring->jrazon            = $request->company;
    // $scoring->jruc              = $request->ruc;
    $scoring->scoreciu          = $request->ciuId;          // <-- join
    $scoring->nestado           = $request->scstatusId;  // <-- join
    // $scoring->jtipo             = $request->companyTypeId;
    $scoring->scorefecha        = $request->birthday;
    $scoring->nocupacion        = $request->ocupationId;
    $scoring->nsensible         = $request->sensibleId;     // <-- join
    $scoring->spep              = $request->pepId;          // <-- join
    // $scoring->jtamano           = $request->companySizeId;
    // $scoring->jcomposicion      = $request->compositionId;
    $scoring->scoreobligado     = $request->obligationId;
    $scoring->scoretransaccion  = $request->transaction;
    $scoring->scorenacionalidad = $request->countryId;
    $scoring->scoreresidencia   = $request->residenceId;
    $scoring->scoreoficina      = $request->officeId;
    $scoring->scoreproducto     = $request->productId;
    $scoring->scoremoneda       = $request->currencyId;
    $scoring->scorefondo        = $request->fundingId;
    $scoring->scoreriesgo       = $request->risk_val;
    $scoring->scoreobserv       = $request->obs;            // <-- ¿ OPTIONAL ?
    /*$scoring->scorecargamasiva  = $request->scoreFile;
    $scoring->scorearchivocarga = $request->file;*/
    $scoring->status            = $request->type === 'register' ? 2 : 1;

    $scoring->save();

    return $scoring;
  }

  public function modifyCompany(Request $request) {
    $scoring = Scoring::find($request->idScoring);
    $scoring->jrazon            = $request->name;
    $scoring->jruc              = $request->dni;
    $scoring->scoreciu          = $request->ciuId;          // <-- join
    $scoring->jtipo             = $request->typeId;
    $scoring->scorefecha        = $request->birthday;
    $scoring->spep              = $request->pepId;          // <-- join
    $scoring->jtamano           = $request->sizeId;
    $scoring->jcomposicion      = $request->compositionId;
    $scoring->scoreobligado     = $request->obligationId;
    $scoring->scoretransaccion  = $request->transaction;
    $scoring->scorenacionalidad = $request->countryId;
    $scoring->scoreresidencia   = $request->residenceId;
    $scoring->scoreoficina      = $request->officeId;
    $scoring->scoreproducto     = $request->productId;
    $scoring->scoremoneda       = $request->currencyId;
    $scoring->scorefondo        = $request->fundingId;
    $scoring->scoreriesgo       = $request->risk_val;
    $scoring->scoreobserv       = $request->obs;            // <-- ¿ OPTIONAL ?
    /*$scoring->scorecargamasiva  = $request->scoreFile;
    $scoring->scorearchivocarga = $request->file;*/
    $scoring->status            = $request->type === 'register' ? 2 : 1;

    $scoring->save();

    return $scoring;
  }

  public function clean($val) {
    if (!is_object($val) && $val !== '') {
      $aux = str_replace("'", "\'", trim($val));
      // $aux = str_replace("/", "\/", $aux);

      $accents = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'];
      $normals = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];

      for ($i=0; $i<10; $i++) {
        $aux = str_replace($accents[$i], $normals[$i], $aux);
      }

      return $aux;
    }
    return $val;
  }

  public function detectPEP($str) {
    $flag = false;
    $cad = strtolower($str);
    if (str_contains($cad, 'pep')) {
      $flag = true;
    }
    return $flag;
  }

  public function findInCommons($lst, $search) {
    if (!isset($search) || empty($search) || !is_string($search)) {
      return;
    }

    $found = '';
    foreach ($lst as $aux) {
      $cad = strtolower($aux['name']);
      $chr = strtolower($search);
      if (str_contains($cad, $chr)) {
        $found = $aux;
        break;
      }
    }
    return $found;
  }

  public function findInCius($lst, $search) {
    if (!isset($search) || empty($search) || !is_string($search)) {
      return;
    }

    $found = '';
    foreach ($lst as $aux) {
      $cad = strtolower($aux['code']);
      $chr = strtolower($search);
      if (str_contains($cad, $chr)) {
        // $aux['code'] = strlen($aux['code']) < 5 ? "0" + $aux['code'] : $aux['code'];
        $found = $aux;
        break;
      }
    }
    return $found;
  }

  public function findInLocations($lst, $search) {
    if (!isset($search) || empty($search) || !is_string($search)) {
      return;
    }

    $found = '';
    foreach ($lst as $aux) {
      $cad = strtolower($aux['name']);
      $chr = strtolower($search);
      if ($cad === $chr) {
        $found = $aux;
        break;
      }
    }
    return $found;
  }

  public function findAge($age) {
    $age = (int) $age;
    $risk_val = 0;
    if ($age >= 0 && $age <= 20) {
      $risk_val = 4;
    } else if ($age > 20 && $age <= 38) {
      $risk_val = 3;
    } else if ($age > 38 && $age <= 57) {
      $risk_val = 2;
    } else if ($age > 57 && $age <= 120) {
      $risk_val = 1;
    } else {
    }
    $res = [
      "name" => $age,
      "risk_val" => $risk_val,
      "weight" => 0.05,
    ];
    return $res;
  }

  public function findAmount($amount) {
    $risk_val = 0;
    if ($amount >= 0 && $amount <= 770.64) {
      $risk_val = 1;
    } else if ($amount > 770.64 && $amount <= 11305.89) {
      $risk_val = 2;
    } else if ($amount > 11305.89 && $amount <= 103690.66) {
      $risk_val = 3;
    } else if ($amount > 103690.66 && $amount <= 196075.42) {
      $risk_val = 4;
    } else if ($amount > 196075.42 && $amount <= 1000000) {
      $risk_val = 5;
    } else {
    }
    $res = [
      "name" => $amount,
      "risk_val" => $risk_val,
      "weight" => 0.05,
    ];
    return $res;
  }

  public function findAgeC($age) {
    $age = (int) $age;
    $risk_val = 0;
    if ($age >= 0 && $age <= 1) {
      $risk_val = 4;
    } else if ($age > 1 && $age <= 3) {
      $risk_val = 3;
    } else if ($age > 3 && $age <= 5) {
      $risk_val = 2;
    } else if ($age > 5 && $age <= 120) {
      $risk_val = 1;
    } else {
    }
    $res = [
      "name" => $age,
      "risk_val" => $risk_val,
      "weight" => 0.05,
    ];
    return $res;
  }

  public function findAmountC($amount) {
    $risk_val = 0;
    if ($amount >= 0 && $amount <= 14520) {
      $risk_val = 1;
    } else if ($amount > 14520 && $amount <= 971637) {
      $risk_val = 2;
    } else if ($amount > 971637 && $amount <= 12896139) {
      $risk_val = 3;
    } else if ($amount > 12896139 && $amount <= 24820642) {
      $risk_val = 4;
    } else if ($amount > 24820642 && $amount <= 1000000000) {
      $risk_val = 5;
    } else {
    }
    $res = [
      "name" => $amount,
      "risk_val" => $risk_val,
      "weight" => 0.05,
    ];
    return $res;
  }

  public function calcAge($birthday) {
    $today = date("Y-m-d");
    $diff = date_diff(date_create($birthday), date_create($today));
    return $diff->format('%y');
  }

  public function calcAmount($item) {
    if (!isset($item)) {
      return 0;
    }
    $val = isset($item['risk_val']) ? $item['risk_val'] : 0;
    $weight = isset($item['weight']) ? $item['weight'] : 0;
    $calc = $val*$weight;
    return $calc;
  }

  public function calcAmountCiu($item, $type) {
    if (!isset($item)) {
      return 0;
    }
    $val = isset($item['risk_val']) ? $item['risk_val'] : 0;
    $weight = 0;
    if ($type === 'natural') {
      $weight = isset($item['weight_dni']) ? $item['weight_dni'] : 0;
    }
    if ($type === 'company') {
      $weight = isset($item['weight_ruc']) ? $item['weight_ruc'] : 0;
    }
    $calc = $val*$weight;
    return $calc;
  }

  public function massiveNatural(Request $request) {
    ini_set('max_execution_time', 120);

    $file = $request->file('file');
    $userId = $request->idUser;

    $collection = (new FastExcel)->sheet(1)->import($file);

    $maxRows = config('constants.excel.maxRowsNegLists');
    if (count($collection) >= $maxRows) {
      return back()->with("fail", __("validation.custom.scoring-massive.max__limit") . " (máx. $maxRows filas)");
    }

    $ocupations = CommonController::listOcupations();
    $obligations = CommonController::listObligations();
    $cius = CommonController::listCius();
    $scstatuss = CommonController::listScStatus();
    // $compositions = CommonController::listCompositions();
    $sensibles = CommonController::listSensibles();
    $peps = CommonController::listPeps();
    $countries = CommonController::listCountries();
    $offices = CommonController::listOffices();
    $products = CommonController::listProducts();
    $currencies = CommonController::listCurrencies();
    $fundings = CommonController::listFundings();
    // $companyTypes = CommonController::listCompanyTypes();
    // $companySizes = CommonController::listCompanySizes();

    $data = [];
    foreach ($collection as $item) {
      $vals = array_values($item);
      if (count($vals) < 18) {
        return back()->with("fail", __("validation.custom.scoring-massive.file__crash"));
      }

      // Client
      $name = $this->clean($vals[0]); // <-- text
      $ocupation = $this->clean($vals[6]);
      $hasCompany = $this->clean($vals[2]); // <-- text
      $hasCompany = strtolower($hasCompany); // <-- text
      $obligation = $this->clean($vals[9]);
      $transaction = $this->clean($vals[10]); // <-- text / evaluate
      $identification = $this->clean($vals[1]); // <-- text
      $birthday = (array) $this->clean($vals[5]); // <-- object / excel
      $birthday = $birthday['date']; // <-- text / evaluate
      $age = $this->calcAge($birthday);
      $ciu = $this->clean($vals[4]);
      $scstatus = $this->clean($vals[3]);
      $sensible = $this->clean($vals[7]);
      $isPEP = $this->detectPEP($sensible); // <-- Boolean
      $pep = $this->clean($vals[8]);

      // Location
      $country = $this->clean($vals[11]);
      $residence = $this->clean($vals[12]);
      $office = $this->clean($vals[13]);

      // Other
      $product = $this->clean($vals[14]);
      $currency = $this->clean($vals[15]);
      $funding = $this->clean($vals[16]);

      // Obs
      $obs = $this->clean($vals[17]); // <-- text

      // Res
      $res = array(
        "name" => $name,
        "ocupation" => $this->findInCommons($ocupations, $ocupation),
        "hasCompany" => $hasCompany,
        "obligation" => $this->findInCommons($obligations, $obligation),
        "transaction" => $transaction,
        "amount" => $this->findAmount($transaction),
        "identification" => $identification,
        "birthday" => $birthday,
        "age" => $this->findAge($age),
        "ciu" => $this->findInCius($cius, $ciu),
        "scstatus" => $this->findInCommons($scstatuss, $scstatus),
        "sensible" => $this->findInCommons($sensibles, $sensible),
        "isPEP" => $isPEP,
        "pep" => $this->findInCommons($peps, $pep),
        "country" => $this->findInLocations($countries, $country),
        "residence" => $this->findInLocations($countries, $residence),
        "office" => $this->findInLocations($offices, $office),
        "product" => $this->findInCommons($products, $product),
        "currency" => $this->findInCommons($currencies, $currency),
        "funding" => $this->findInCommons($fundings, $funding),
        "obs" => $obs,
      );

      // Calculate Risk
      $risks = array(
        "client" => array("zTotal" => 0),
        "location" => array("zTotal" => 0),
        "other" => array("zTotal" => 0),
        "zTotal" => 0,
      );

      // --- Begin Client
      $keys = ['ocupation', 'obligation', 'amount', 'age', 'sensible'];
      $keys[] = $res['hasCompany'] === 'si' ? 'ciu' : 'scstatus';
      if ($res['isPEP']) {
        $keys[] = 'pep';
      }
      foreach ($keys as $key) {
        if ($key === 'ciu') {
          $calc = $this->calcAmountCiu($res['ciu'], 'natural');
        } else {
          $calc = $this->calcAmount($res[$key]);
        }
        $risks['client'][$key] = $calc;
        $risks['client']['zTotal'] = $risks['client']['zTotal'] + $calc;
      }
      $risks['zTotal'] = $risks['zTotal'] + $risks['client']['zTotal'];
      // --- End Client

      // --- Begin Location
      $keys = ['country', 'office', 'residence'];
      foreach ($keys as $key) {
        $calc = $this->calcAmount($res[$key]);
        $risks['location'][$key] = $calc;
        $risks['location']['zTotal'] = $risks['location']['zTotal'] + $calc;
      }
      $risks['zTotal'] = $risks['zTotal'] + $risks['location']['zTotal'];
      // --- End Location

      // --- Begin Other
      $keys = ['product', 'currency', 'funding'];
      foreach ($keys as $key) {
        $calc = $this->calcAmount($res[$key]);
        $risks['other'][$key] = $calc;
        $risks['other']['zTotal'] = $risks['other']['zTotal'] + $calc;
      }
      $risks['zTotal'] = $risks['zTotal'] + $risks['other']['zTotal'];
      // --- End Other

      $res['__risks'] = $risks;
      // $data[] = $res;
      $data[] = [
        "userid" => $userId,
        "scoretipo" => "N",
        "scorecreacion" => date("Y-m-d h:i:s"),
        "nnombre" => $name,
        "ndni" => $identification,
        "nconnegocio" => $hasCompany,
        // "jrazon" => "",
        // "jruc" => "",
        "scoreciu" => isset($res['ciu']['code']) ? $res['ciu']['code'] : null,
        "nestado" => isset($res['scstatus']['id']) ? $res['scstatus']['id'] : null,
        // "jtipo" => "",
        "scorefecha" => isset($res['birthday']) ? $res['birthday'] : null,
        "nocupacion" => isset($res['ocupation']['id']) ? $res['ocupation']['id'] : null,
        "nsensible" => isset($res['sensible']['id']) ? $res['sensible']['id'] : null,
        "spep" => isset($res['pep']['id']) ? $res['pep']['id'] : null,
        // "jtamano" => "",
        // "jcomposicion" => "",
        "scoreobligado" => isset($res['obligation']['id']) ? $res['obligation']['id'] : null,
        "scoretransaccion" => $res['transaction'],
        "scorenacionalidad" => isset($res['country']['id']) ? $res['country']['id'] : null,
        "scoreresidencia" => isset($res['residence']['id']) ? $res['residence']['id'] : null,
        "scoreoficina" => isset($res['office']['id']) ? $res['office']['id'] : null,
        "scoreproducto" => isset($res['product']['id']) ? $res['product']['id'] : null,
        "scoremoneda" => isset($res['currency']['id']) ? $res['currency']['id'] : null,
        "scorefondo" => isset($res['funding']['id']) ? $res['funding']['id'] : null,
        "scoreriesgo" => $res['__risks']['zTotal'],
        "scoreobserv" => $res['obs'],
        "scorecargamasiva" => "",
        "scorearchivocarga" => "",
        "status" => 2,
      ];
    }

    // Response
    $response = Scoring::insert($data);
    // return $response;
    return back()->with("success", "¡Carga Masiva Correcta!");
  }

  public function massiveCompany(Request $request) {
    ini_set('max_execution_time', 120);

    $file = $request->file('file');
    $userId = $request->idUser;

    $collection = (new FastExcel)->sheet(1)->import($file);

    $maxRows = config('constants.excel.maxRowsNegLists');
    if (count($collection) >= $maxRows) {
      return back()->with("fail", __("validation.custom.scoring-massive.max__limit") . " (máx. $maxRows filas)");
    }

    $types = CommonController::listCompanyTypes();
    $sizes = CommonController::listCompanySizes();
    $obligations = CommonController::listObligations();
    $cius = CommonController::listCius();
    $compositions = CommonController::listCompositions();
    $peps = CommonController::listPeps();
    $countries = CommonController::listCountries();
    $offices = CommonController::listOffices();
    $products = CommonController::listProducts();
    $currencies = CommonController::listCurrencies();
    $fundings = CommonController::listFundings();

    $data = [];
    foreach ($collection as $item) {
      $vals = array_values($item);
      if (count($vals) < 17) {
        return back()->with("fail", __("validation.custom.scoring-massive.file__crash"));
      }

      // Client
      $name = $this->clean($vals[0]); // <-- text
      $type = $this->clean($vals[3]);
      $size = $this->clean($vals[5]);
      $obligation = $this->clean($vals[8]);
      $transaction = $this->clean($vals[9]); // <-- text / evaluate
      $identification = $this->clean($vals[1]); // <-- text
      $birthday = (array) $this->clean($vals[4]); // <-- object / excel
      $birthday = $birthday['date']; // <-- text / evaluate
      $age = $this->calcAge($birthday);
      $ciu = $this->clean($vals[2]);
      $composition = $this->clean($vals[6]);
      $isPEP = $this->detectPEP($composition); // <-- Boolean
      $pep = $this->clean($vals[7]);

      // Location
      $country = $this->clean($vals[10]);
      $residence = $this->clean($vals[11]);
      $office = $this->clean($vals[12]);

      // Other
      $product = $this->clean($vals[13]);
      $currency = $this->clean($vals[14]);
      $funding = $this->clean($vals[15]);

      // Obs
      $obs = $this->clean($vals[16]); // <-- text

      // Res
      $res = array(
        "name" => $name,
        "type" => $this->findInCommons($types, $type),
        "size" => $this->findInCommons($sizes, $size),
        "obligation" => $this->findInCommons($obligations, $obligation),
        "transaction" => $transaction,
        "amount" => $this->findAmountC($transaction),
        "identification" => $identification,
        "birthday" => $birthday,
        "age" => $this->findAgeC($age),
        "ciu" => $this->findInCius($cius, $ciu),
        "composition" => $this->findInCommons($compositions, $composition),
        "isPEP" => $isPEP,
        "pep" => $this->findInCommons($peps, $pep),
        "country" => $this->findInLocations($countries, $country),
        "residence" => $this->findInLocations($countries, $residence),
        "office" => $this->findInLocations($offices, $office),
        "product" => $this->findInCommons($products, $product),
        "currency" => $this->findInCommons($currencies, $currency),
        "funding" => $this->findInCommons($fundings, $funding),
        "obs" => $obs,
      );

      // Calculate Risk
      $risks = array(
        "client" => array("zTotal" => 0),
        "location" => array("zTotal" => 0),
        "other" => array("zTotal" => 0),
        "zTotal" => 0,
      );

      // --- Begin Client
      $keys = ['type', 'size', 'obligation', 'amount', 'age', 'ciu', 'composition'];
      if ($res['isPEP']) {
        $keys[] = 'pep';
      }
      foreach ($keys as $key) {
        if ($key === 'ciu') {
          $calc = $this->calcAmountCiu($res['ciu'], 'company');
        } else {
          $calc = $this->calcAmount($res[$key]);
        }
        $risks['client'][$key] = $calc;
        $risks['client']['zTotal'] = $risks['client']['zTotal'] + $calc;
      }
      $risks['zTotal'] = $risks['zTotal'] + $risks['client']['zTotal'];
      // --- End Client

      // --- Begin Location
      $keys = ['country', 'office', 'residence'];
      foreach ($keys as $key) {
        $calc = $this->calcAmount($res[$key]);
        $risks['location'][$key] = $calc;
        $risks['location']['zTotal'] = $risks['location']['zTotal'] + $calc;
      }
      $risks['zTotal'] = $risks['zTotal'] + $risks['location']['zTotal'];
      // --- End Location

      // --- Begin Other
      $keys = ['product', 'currency', 'funding'];
      foreach ($keys as $key) {
        $calc = $this->calcAmount($res[$key]);
        $risks['other'][$key] = $calc;
        $risks['other']['zTotal'] = $risks['other']['zTotal'] + $calc;
      }
      $risks['zTotal'] = $risks['zTotal'] + $risks['other']['zTotal'];
      // --- End Other

      $res['__risks'] = $risks;
      // $data[] = $res;
      $data[] = [
        "userid" => $userId,
        "scoretipo" => "J",
        "scorecreacion" => date("Y-m-d h:i:s"),
        "jrazon" => $name,
        "jruc" => $identification,
        "scoreciu" => isset($res['ciu']['code']) ? $res['ciu']['code'] : null,
        "jtipo" => isset($res['type']['id']) ? $res['type']['id'] : null,
        "scorefecha" => isset($res['birthday']) ? $res['birthday'] : null,
        "spep" => isset($res['pep']['id']) ? $res['pep']['id'] : null,
        "jtamano" => isset($res['size']['id']) ? $res['size']['id'] : null,
        "jcomposicion" => isset($res['composition']['id']) ? $res['composition']['id'] : null,
        "scoreobligado" => isset($res['obligation']['id']) ? $res['obligation']['id'] : null,
        "scoretransaccion" => $res['transaction'],
        "scorenacionalidad" => isset($res['country']['id']) ? $res['country']['id'] : null,
        "scoreresidencia" => isset($res['residence']['id']) ? $res['residence']['id'] : null,
        "scoreoficina" => isset($res['office']['id']) ? $res['office']['id'] : null,
        "scoreproducto" => isset($res['product']['id']) ? $res['product']['id'] : null,
        "scoremoneda" => isset($res['currency']['id']) ? $res['currency']['id'] : null,
        "scorefondo" => isset($res['funding']['id']) ? $res['funding']['id'] : null,
        "scoreriesgo" => $res['__risks']['zTotal'],
        "scoreobserv" => $res['obs'],
        "scorecargamasiva" => "",
        "scorearchivocarga" => "",
        "status" => 2,
      ];
    }

    // Response
    $response = Scoring::insert($data);
    // return $response;
    return back()->with("success", "¡Carga Masiva Correcta!");
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
    $scoringId = $request->scoringId;

    $scoring = Scoring::find($scoringId);
    $scoring->userIds = $this->str2lst($scoring->userIds);

    $userIds = $scoring->userIds;
    if (in_array($userId, $userIds)) {
      return $scoring;
    }
    $userIds[] = strval($userId);
    $scoring->userIds = $userIds;
    $scoring->save();

    return $scoring;
  }
}
