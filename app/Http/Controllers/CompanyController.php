<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CompanyUser;

use DB;

class CompanyController extends Controller {
  public function listUsers($iduser) {
    $user = CompanyUser::where("wp_users_id", "=", $iduser)->first();
    if (!$user) {
      return [];
    }
    $users = CompanyUser::select(
      "tbd_empresauser.id",
      "tbd_empresauser.tbd_empresa_id as companyId",
      "tbd_empresauser.wp_users_id as userId",
      // "wp_users.display_name as fullname",
      DB::raw("CONCAT(wp_users.display_name,' ',IFNULL(wp_users.lastname,'')) as fullname"),
      "wp_users.dni as dni"
      )
      ->where("tbd_empresauser.tbd_empresa_id", "=", $user->tbd_empresa_id)
      ->where("wp_users.user_status", "=", 1)
      ->where("tbd_empresauser.wp_users_id", "!=", $iduser)
      ->join("wp_users", "wp_users.ID", '=', "tbd_empresauser.wp_users_id")
      ->get();

    return $users;
  }
}
