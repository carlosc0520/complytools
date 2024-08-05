<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CompanyUser;
use App\Models\NegativeLists;
use App\Models\NegativeListsMeta;
use App\Models\NegativeListsCounter;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Illuminate\Http\Request;

use DB;
use Session;
use PDF;
use Rap2hpoutre\FastExcel\FastExcel;

class NegativeListsController extends Controller
{
  public function viewList()
  {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $userId = Session::get("loginId");
    $counter = NegativeListsCounter::select(
      "id",
      "user as userId",
      "contador as counter",
      "maximo as limit"
    )
      ->where('user', '=', $userId)
      ->first();
    if (!$counter) {
      $counter = array(
        "counter" => 0,
        "limit" => 0,
      );
    }

    return view("pages.negativelists.index", compact('counter', 'userId'));
  }

  public function viewListSearches()
  {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $userId = Session::get("loginId");

    return view("pages.negativelists.searches.index", compact('userId'));
  }

  public function viewAdmin()
  {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $userId = Session::get("loginId");

    return view("pages.negativelists.admin.index", compact('userId'));
  }

  public function getCounter($iduser)
  {
    $neglstuser = NegativeListsCounter::where("user", "=", $iduser)->first();

    return $neglstuser->contador;
  }

  public function dtTable(Request $request)
  {
    $data = NegativeListsMeta::select(
      "tbl_busqueda.busquedaid AS id",
      DB::raw("CONCAT(tbl_info.infonombres, ', ' ,tbl_info.infoapellidos) AS fullname"),
      "tbl_info.infoidentifica AS document",
      "tbl_info.infoprog AS type",
      "tbl_tipo.color AS color",
      "tbl_busqueda.busquedafecha AS created_at",
      "tbl_busqueda.busquedainfo AS idNegativeLists"
    )
      ->join('tbl_info', 'tbl_info.infoid', '=', 'tbl_busqueda.busquedainfo')
      ->leftjoin('tbl_tipo', 'tbl_tipo.name', '=', 'tbl_info.infoprog')
      ->where('tbl_busqueda.busquedauser', '=', $request->userId)
      ->orderBy('tbl_busqueda.busquedaid', 'desc')
      ->get();

    return datatables()->of($data)
      ->addIndexColumn()
      ->addColumn('adn', function ($item) {
        $btn = "<div class='flex justify-center'><input id='{$item->id}' type='checkbox' class='checkbox' /></div>";
        return $btn;
      })
      ->addColumn('type_color', function ($item) {
        /*$btn = "<div class='flex justify-center'>
          <span style='color: {$item->color}'>{$item->type}</span>
        </div>";
        return $btn;*/
        return $item->color . "|" . $item->type;
      })
      ->addColumn('actions', function ($item) {
        return $item->idNegativeLists;
      })
      ->rawColumns(['adn', 'action'])
      ->make(true);
  }

  function getRegex($text)
  {
    $textSplitted = explode(" ", $text);
    $str = "%";
    foreach ($textSplitted as $aux) {
      $str = $str . trim($aux) . "%";
    }
    return $str;
  }

  function increaseSearches($userId, $infoIds)
  {
    $neglstuser = NegativeListsCounter::where("user", "=", $userId)->first();
    if ($neglstuser->contador < $neglstuser->maximo) {
      NegativeListsCounter::where("id", "=", $neglstuser->id)->update(['contador' => $neglstuser->contador + 1]);
    }

    foreach ($infoIds as $infoId) {
      $search = new NegativeListsMeta();
      $search->busquedauser = $userId;
      $search->busquedafecha = date("Y-m-d h:i:s");
      $search->busquedainfo = $infoId;
      $search->save();
    }
  }

  public function dtTableSearch(Request $request)
  {
    try {
      //code...
      $typeSearch = $request->typeSearch;
      $userId = $request->userId;
      $name = $request->name;
      $lastname = $request->lastname;
      $ruc = $request->ruc;

      if (!isset($name) && !isset($lastname) && !isset($ruc)) {
        return true;
      }

      $data = NegativeLists::select(
        "tbl_info.infoid AS id",
        "tbl_info.infotipo AS tt",
        DB::raw("CONCAT(tbl_info.infonombres, ' ', tbl_info.infoapellidos) AS fullname"),
        "tbl_info.infoprog AS type",
        "tbl_info.infocargo AS position",
        "tbl_info.infolink AS link",
        "tbl_info.infoalias AS alias",
        "tbl_info.infoidentifica AS ruc",
        "tbl_info.infopasaporte AS passport",
        "tbl_info.infonacionalidad AS nation",
        "tbl_info.infogenero AS gender",
        "tbl_info.infomas AS other",
        "tbl_info.infofecha AS date_at",
        "tbl_info.infolugar AS location",
        "tbl_tipo.color AS color"
      )
        ->leftjoin('tbl_tipo', 'tbl_tipo.name', '=', 'tbl_info.infoprog');


      if ($typeSearch !== 'same_lastname') {

        // -- Individual --
        if (isset($name) && !isset($lastname) && !isset($ruc)) {
          // Only name
          $data = $data
            ->where('tbl_info.infonombres', 'like', $this->getRegex($name))
            ->orWhere('tbl_info.infoalias', 'like', $this->getRegex($name));
        }
        if (!isset($name) && isset($lastname) && !isset($ruc)) {
          // Only lastname
          $data = $data
            ->where('tbl_info.infoapellidos', 'like', $this->getRegex($lastname))
            ->orWhere('tbl_info.infoalias', 'like', $this->getRegex($lastname));
        }
        if (!isset($name) && !isset($lastname) && isset($ruc)) {
          // Only ruc
          $data = $data
            ->where('tbl_info.infoidentifica', 'like', $this->getRegex($ruc))
            ->orWhere('tbl_info.infopasaporte', 'like', $this->getRegex($ruc));
        }
        // -- Group --
        if (isset($name) && isset($lastname) && !isset($ruc)) {
          // Set name, lastname
          $data = $data
            ->where('tbl_info.infonombres', 'like', $this->getRegex($name))
            ->where('tbl_info.infoapellidos', 'like', $this->getRegex($lastname));
        }
        if (!isset($name) && isset($lastname) && isset($ruc)) {
          // Set lastname, ruc
          $data = $data
            ->where('tbl_info.infoapellidos', 'like', $this->getRegex($lastname))
            ->orwhere('tbl_info.infoidentifica', 'like', $this->getRegex($ruc));
        }
        if (isset($name) && !isset($lastname) && isset($ruc)) {
          // Set name, ruc
          $data = $data
            ->where('tbl_info.infonombres', 'like', $this->getRegex($name))
            ->orwhere('tbl_info.infoidentifica', 'like', $this->getRegex($ruc));
        }
        // -- All --
        if (isset($name) && isset($lastname) && isset($ruc)) {
          $data = $data
            ->where('tbl_info.infonombres', 'like', $this->getRegex($name))
            ->where('tbl_info.infoapellidos', 'like', $this->getRegex($lastname))
            ->orwhere('tbl_info.infoidentifica', 'like', $this->getRegex($ruc));
        }
      } else {
        // -- Group --
        if (isset($lastname) && !isset($ruc)) {
          // Set name, lastname
          if (isset($name)) {
            $data = $data->where('tbl_info.infonombres', 'not like', $name);
          }
          $data = $data->where('tbl_info.infoapellidos', 'like', $this->getRegex($lastname));
        }
        if (!isset($lastname) && isset($ruc)) {
          // Set name, lastname
          if (isset($name)) {
            $data = $data->where('tbl_info.infonombres', 'not like', $name);
          }
          $data = $data->where('tbl_info.infoidentifica', 'like', $this->getRegex($ruc));
        }
        if (!isset($lastname) && !isset($ruc)) {
          // Set name, lastname
          if (isset($name)) {
            $data = $data->where('tbl_info.infonombres', 'not like', $name);
          }
          $data = $data->where('tbl_info.infonombres', '=', 'Empty--Empty');
        }
        // -- All --
        if (isset($lastname) && isset($ruc)) {
          if (isset($name)) {
            $data = $data->where('tbl_info.infonombres', 'not like', $name);
          }
          $data = $data
            ->where('tbl_info.infoapellidos', 'like', $this->getRegex($lastname))
            ->orwhere('tbl_info.infoidentifica', 'like', $this->getRegex($ruc));
        }
      }

      $data = $data->orderBy('tbl_info.infoid', 'desc')->get();

      return datatables()->of($data)
        ->addIndexColumn()
        ->addColumn('adn', function ($item) {
          $btn = "<div class='flex justify-center'><input id='{$item->id}' type='checkbox' class='checkbox' /></div>";
          return $btn;
        })
        ->addColumn('type_color', function ($item) {
          $btn = "<div class='flex justify-center'>
          <span style='color: {$item->color}'>{$item->type}</span>
        </div>";
          return $btn;
        })
        ->addColumn('actions', function ($item) {
          return $item->id;
        })
        ->rawColumns(['adn', 'type_color', 'action'])
        ->make(true);
    } catch (\Throwable $th) {
      var_dump($th->getMessage());
    }
  }

  function cleanStr($str)
  {
    $aux = trim($str);
    return strtolower($aux);
  }

  public function massive(Request $request)
  {
    ini_set('max_execution_time', 120);

    $userId = $request->idUser;
    $type = $request->type;
    $file = $request->file('file');

    $user = User::where("ID", "=", $userId)->first();
    $collection = (new FastExcel)->sheet(1)->import($file);

    $maxRows = config('constants.excel.maxRowsNegLists');
    if (count($collection) >= $maxRows) {
      return back()->with("fail", __("validation.custom.negativelists-massive.max__limit") . " (máx. $maxRows filas)");
    }

    $data = [];
    foreach ($collection as $item) {
      $vals = array_values($item);
      $data[] = array(
        "name" => isset($vals[0]) ? str_replace("'", "\'", trim($vals[0])) : '',
        "lastname" => isset($vals[1]) ? str_replace("'", "\'", trim($vals[1])) : '',
        "ruc" => isset($vals[2]) ? str_replace("'", "\'", trim($vals[2])) : '',
      );
    }

    $names = "";
    $lastnames = "";
    $rucs = "";
    foreach ($data as $te) {
      $name = $te['name'];
      $lastname = $te['lastname'];
      $ruc = $te['ruc'];
      if (isset($name) && !empty($name)) $names = "$names,'$name'";
      if (isset($lastname) && !empty($lastname)) $lastnames = "$lastnames,'$lastname'";
      if (isset($ruc) && !empty($ruc)) $rucs = "$rucs,'$ruc'";
    }
    $names = substr($names, 1);
    $lastnames = substr($lastnames, 1);
    $rucs = substr($rucs, 1);

    $query = "SELECT
        infoid,
        infonombres,
        infoapellidos,
        infoidentifica,
        infoprog
      FROM tbl_info ";
    if (!empty($names) && empty($lastnames) && empty($rucs)) {
      $query = $query . "WHERE lower(infonombres) IN ($names);";
    }
    if (empty($names) && !empty($lastnames) && empty($rucs)) {
      $query = $query . "WHERE lower(infoapellidos) IN ($lastnames);";
    }
    if (empty($names) && empty($lastnames) && !empty($rucs)) {
      $query = $query . "WHERE infoidentifica IN ($rucs);";
    }
    if (!empty($names) && !empty($lastnames) && empty($rucs)) {
      $query = $query . "WHERE lower(infonombres) IN ($names) AND lower(infoapellidos) IN ($lastnames);";
    }
    if (!empty($names) && empty($lastnames) && !empty($rucs)) {
      $query = $query . "WHERE lower(infonombres) IN ($names) AND infoidentifica IN ($rucs);";
    }
    if (empty($names) && !empty($lastnames) && !empty($rucs)) {
      $query = $query . "WHERE lower(infoapellidos) IN ($lastnames) AND infoidentifica IN ($rucs);";
    }
    if (!empty($names) && !empty($lastnames) && !empty($rucs)) {
      $query = $query . "WHERE lower(infonombres) IN ($names) AND lower(infoapellidos) IN ($lastnames) OR infoidentifica IN ($rucs);";
    }
    $result = DB::select($query);

    $response = [];
    $infoIds = [];
    foreach ($data as $item) {
      $name = $item['name'];
      $lastname = $item['lastname'];
      $ruc = $item['ruc'];
      $aux = array("name" => $name, "lastname" => $lastname, "ruc" => $ruc, "matched" => 'NO', "type" => '');

      foreach ($result as $res) {
        $rucDB = $res->infoidentifica;
        $nameDB = $res->infonombres;
        $lastnameDB = $res->infoapellidos;
        $typeDB = $res->infoprog;

        // Individual
        if (!empty($ruc) && empty($name) && empty($lastname)) { // ruc
          if ($rucDB === $ruc) {
            $aux['matched'] = 'SÍ';
            $aux['type'] = $typeDB;
          }
        }
        if (empty($ruc) && !empty($name) && empty($lastname)) { // name
          if (str_contains($this->cleanStr($nameDB), $this->cleanStr($name))) {
            $aux['matched'] = 'SÍ';
            $aux['type'] = $typeDB;
          }
        }
        if (empty($ruc) && empty($name) && !empty($lastname)) { // lastname
          if (str_contains($this->cleanStr($lastnameDB), $this->cleanStr($lastname))) {
            $aux['matched'] = 'SÍ';
            $aux['type'] = $typeDB;
          }
        }
        // Group
        if (!empty($ruc) && !empty($name) && empty($lastname)) { // ruc, name
          if ($rucDB === $ruc) {
            $aux['matched'] = 'SÍ';
            $aux['type'] = $typeDB;
          } else {
            if (str_contains($this->cleanStr($nameDB), $this->cleanStr($name))) {
              $aux['matched'] = 'SÍ';
              $aux['type'] = $typeDB;
            }
          }
        }
        if (!empty($ruc) && empty($name) && !empty($lastname)) { // ruc, lastname
          if ($rucDB === $ruc) {
            $aux['matched'] = 'SÍ';
            $aux['type'] = $typeDB;
          } else {
            if (str_contains($this->cleanStr($lastnameDB), $this->cleanStr($lastname))) {
              $aux['matched'] = 'SÍ';
              $aux['type'] = $typeDB;
            }
          }
        }
        if (empty($ruc) && !empty($name) && !empty($lastname)) {  // name, lastname
          if ($this->cleanStr($nameDB) === $this->cleanStr($name) && $this->cleanStr($lastnameDB) === $this->cleanStr($lastname)) {
            $aux['matched'] = 'SÍ';
            $aux['type'] = $typeDB;
          }
        }
        // All
        if (!empty($ruc) && !empty($name) && !empty($lastname)) { // ruc, name, lastname
          if ($rucDB === $ruc) {
            $aux['matched'] = 'SÍ';
            $aux['type'] = $typeDB;
          } else {
            if ($this->cleanStr($nameDB) === $this->cleanStr($name) && $this->cleanStr($lastnameDB) === $this->cleanStr($lastname)) {
              $aux['matched'] = 'SÍ';
              $aux['type'] = $typeDB;
            }
          }
        }
      }
      $response[] = $aux;
    }

    $generatedAt = date("Ymd");
    $filename = "Resultado$generatedAt.$type";

    if ($type === 'xlsx') {
      $this->increaseSearches($userId, $infoIds);

      $excel = new FastExcel($response);
      return ($excel)->download($filename);
    }
    if ($type === 'pdf') {
      $this->increaseSearches($userId, $infoIds);

      $path = 'pdfs.neglistmassive';
      $result = array(
        "meta" => [
          "fullname" => isset($user) ? $user->display_name . " " . $user->lastname : '',
          "created_at" => date("d-m-Y h:i:s A"),
        ],
        "data" => $response,
      );
      view()->share($path, $result);

      $pdf = PDF::loadView($path, ["result" => $result]);
      $pdf->setPaper('A4', 'landscape');
      return ($pdf)->download($filename);
    }

    return true;
  }

  public function details($iduser, $id, $issearch)
  {
    if (!isset($iduser) && !isset($id)) {
      return false;
    }

    if ($issearch === 'true') {
      $this->increaseSearches($iduser, [$id]);
    }

    $negativelist = NegativeLists::select(
      "tbl_info.infoid AS id",
      "tbl_info.infotipo AS tt",
      "tbl_info.infoapellidos AS lastname",
      "tbl_info.infonombres AS name",
      "tbl_info.infoprog AS type",
      "tbl_info.infocargo AS position",
      "tbl_info.infolink AS link",
      "tbl_info.infoalias AS alias",
      "tbl_info.infoidentifica AS ruc",
      "tbl_info.infopasaporte AS passport",
      "tbl_info.infonacionalidad AS nation",
      "tbl_info.infogenero AS gender",
      "tbl_info.infomas AS other",
      "tbl_info.infofecha AS date_at",
      "tbl_info.infolugar AS location",
      "tbl_tipo.color AS color"
    )
      ->where('tbl_info.infoid', '=', $id)
      ->leftjoin('tbl_tipo', 'tbl_tipo.name', '=', 'tbl_info.infoprog')
      ->first();

    return $negativelist;
  }

  public function detailsPDF($iduser, $id)
  {
    if (!isset($iduser) && !isset($id)) {
      return false;
    }

    $user = User::where("ID", "=", $iduser)->first();

    $negativelist = NegativeLists::select(
      "tbl_info.infoid AS id",
      "tbl_info.infotipo AS tt",
      "tbl_info.infoapellidos AS lastname",
      "tbl_info.infonombres AS name",
      "tbl_info.infoprog AS type",
      "tbl_info.infocargo AS position",
      "tbl_info.infolink AS link",
      "tbl_info.infoalias AS alias",
      "tbl_info.infoidentifica AS ruc",
      "tbl_info.infopasaporte AS passport",
      "tbl_info.infonacionalidad AS nation",
      "tbl_info.infogenero AS gender",
      "tbl_info.infomas AS other",
      "tbl_info.infofecha AS date_at",
      "tbl_info.infolugar AS location",
      "tbl_tipo.color AS color"
    )
      ->where('tbl_info.infoid', '=', $id)
      ->leftjoin('tbl_tipo', 'tbl_tipo.name', '=', 'tbl_info.infoprog')
      ->first();

    $path = 'pdfs.neglistdetails';
    $result = array(
      "meta" => [
        "fullname" => isset($user) ? $user->display_name . " " . $user->lastname : '',
        "created_at" => date("d-m-Y h:i:s A"),
      ],
      "data" => $negativelist,
    );
    view()->share($path, $result);

    $pdf = PDF::loadView($path, ["result" => $result]);

    return $pdf->download('Detalle-de-Lista-Negativa.pdf');
  }

  public function detailsPDFEmpty($iduser, $search)
  {
    if (!isset($iduser) && !isset($search)) {
      return false;
    }

    $user = User::where("ID", "=", $iduser)->first();

    $path = 'pdfs.neglistempty';
    $result = array(
      "meta" => [
        "fullname" => isset($user) ? $user->display_name . " " . $user->lastname : '',
        "created_at" => date("d-m-Y h:i:s A"),
      ],
      "data" => $search,
    );
    view()->share($path, $result);

    $pdf = PDF::loadView($path, ["result" => $result]);

    return $pdf->download('Resultado-de-Lista-Negativa.pdf');
  }

  public function searchesByCompany($iduser)
  {
    $user = CompanyUser::where("wp_users_id", "=", $iduser)->first();
    $users = CompanyUser::where("tbd_empresa_id", "=", $user->tbd_empresa_id)->get();
    $userIds = [];
    foreach ($users as $usr) {
      $userIds[] = $usr->wp_users_id;
    }
    $data = User::select(
      "wp_users.ID as userId",
      "tbl_busquedauser.contador as count",
      "wp_users.user_login as username",
      DB::raw("CONCAT(wp_users.display_name,' ',IFNULL(wp_users.lastname,'')) as name"),
      "wp_users.user_email as email"
    )
      ->leftjoin("tbl_busquedauser", "tbl_busquedauser.user", "=", "wp_users.ID")
      ->whereIn("wp_users.ID", $userIds)
      ->where("wp_users.user_status", 1) 
      ->orderBy("wp_users.display_name", "asc")
      ->get();

    return $data;
  }

  function prettyDate($str)
  {
    // $date = "";
    if (is_object($str)) {
      return $str->format('d/m/Y');
    }
    /*$aux = explode("/", $str);
    if (count($aux) == 3) {
      $day = $aux[1] > 10 ? $aux[1] : "0{$aux[1]}";
      $month = $aux[0] > 10 ? $aux[0] : "0{$aux[0]}";
      $year = $aux[2];
      $date = "{$day}/{$month}/{$year}";
    }*/
    return $str;
  }

  function cleanText($text)
  {
    if (!isset($text)) {
      return "";
    }
    $res_1 = str_replace("'", "\'", trim($text));
    $res = str_replace('"', '\"', $res_1);
    return $res;
  }

  public function massiveAdmin(Request $request)
  {
    ini_set('max_execution_time', 120);

    $type = $request->type;
    $file = $request->file('file');

    // $user = User::where("ID", "=", $userId)->first();
    $collection = (new FastExcel)->sheet(1)->import($file);

    $maxRows = config('constants.excel.maxRowsNegListsAdmin');
    if (count($collection) >= $maxRows) {
      $msg = __("validation.custom.negativelists-massive.max__limit") . " (máx. $maxRows filas)";
      return response()->json(['error' => $msg], 400);
    }

    $data = [];
    foreach ($collection as $item) {
      $vals = array_values($item);
      $data[] = array(
        "infoid" => $vals[0],
        "infotipo" => $this->cleanText($vals[1]),
        "infoapellidos" => $this->cleanText($vals[2]),
        "infonombres" => $this->cleanText($vals[3]),
        "infoprog" => $this->cleanText($vals[4]),
        "infocargo" => $this->cleanText($vals[5]),
        "infolink" => $this->cleanText($vals[6]),
        "infoalias" => $this->cleanText($vals[7]),
        "infoidentifica" => $this->cleanText($vals[8]),
        "infopasaporte" => $this->cleanText($vals[9]),
        "infonacionalidad" => $this->cleanText($vals[10]),
        "infogenero" => $this->cleanText($vals[11]),
        "infomas" => $this->cleanText($vals[12]),
        "infofecha" => $this->prettyDate($vals[13]),
        "infolugar" => $this->cleanText($vals[14]),
      );
    }

    if ($type === 'create') {
      try {
        NegativeLists::insert($data);
      } catch (\Exception $e) {
        // return back()->with("fail", __("validation.custom.negativelists-massive-admin.error__cross__add"));
        return response()->json(['error' => $e->getMessage()], 400);
      }
    }

    if ($type === 'modify') {
      try {
        foreach ($data as $item) {
          $id = $item["infoid"];
          unset($item[1]);
          NegativeLists::where('infoid', '=', $id)->update($item);
        }
      } catch (\Exception $e) {
        // return back()->with("fail", __("validation.custom.negativelists-massive-admin.error__cross__upd"));
        return response()->json(['error' => $e->getMessage()], 400);
      }
    }

    return $data;
  }

  public function assign(Request $request)
  {
    $search = new NegativeListsMeta();
    $search->busquedauser = $request->userId;
    $search->busquedafecha = now();
    $search->busquedainfo = $request->infoId;
    $search->save();
    return $search;
  }
  public function mailMasivo()
  {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ccarbajalmt0520@gmail.com';
    $mail->Password = 'qcigvfwwdyrwelib';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    try {
      $users = User::where('indicador', 1)->get();
      foreach ($users as $user) {
        $infos = DB::table('wp_users as A')
          ->join('tbl_busqueda as B', 'B.busquedauser', '=', 'A.ID')
          ->join('tb_notify_info as C', 'C.IDINFO', '=', 'B.busquedainfo')
          ->join('tbl_info as D', 'D.INFOID', '=', 'C.IDINFO')
          ->where('A.ID', $user->ID)
          ->select(
            'D.infoid',
            'D.infotipo',
            'D.infoapellidos',
            'D.infonombres',
            'D.infoprog',
            'D.infocargo',
            'D.infolink',
            'D.infoalias',
            'D.infotipodocumento',
            'D.infoidentifica',
            'D.infopasaporte',
            'D.infonacionalidad',
            'D.infogenero',
            'D.infomas',
            'D.infofecha',
            'D.infolugar'
          )
          ->groupBy(
            'D.infoid',
            'D.infotipo',
            'D.infoapellidos',
            'D.infonombres',
            'D.infoprog',
            'D.infocargo',
            'D.infolink',
            'D.infoalias',
            'D.infotipodocumento',
            'D.infoidentifica',
            'D.infopasaporte',
            'D.infonacionalidad',
            'D.infogenero',
            'D.infomas',
            'D.infofecha',
            'D.infolugar'
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
      echo json_encode(['status' => 'success', 'message' => 'Correo enviado correctamente']);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Error al enviar el correo: ' . $mail->ErrorInfo]);
    }
  }


  // addProgramada requerst, tambein se enviara por form-data infonombres y infoapellidos
  public function addProgramada(Request $request)
  {
    echo '<pre>';
    var_dump($request->all());
    echo '</pre>';
    $info = new NegativeLists();
    $info->infonombres = $request->infonombres;
    $info->infoapellidos = $request->infoapellidos;
    $info->user = $request->userId;

    var_dump($info);
    return $info;
  }
}
