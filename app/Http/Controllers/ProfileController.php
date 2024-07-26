<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

use WpPassword;
use Session;
use Cookie;

class ProfileController extends Controller {
  public function __invoke() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $session_id = Session::get("loginId");

    $user = User::find($session_id);

    return view("pages.profile", compact('user'));
  }

  public function uploadAvatar(Request $request) {
    // Input
    $idUser = $request->id;
    $avatar = $request->file;

    // Data
    $store = 'public/users/' . $idUser;
    $filename = time() . '.' . $avatar->extension();

    // Process
    Storage::disk('local')->putFileAs($store, $avatar, $filename);

    $struct = [
      'avatar' => "/storage/users/$idUser/$filename",
    ];

    User::where('ID', '=', $idUser)->update($struct);
    $user = User::where("ID", "=", $idUser)->first();

    Cookie::forget('user_avatar');
    Cookie::queue('user_avatar', $user->avatar, 1000);

    // Response
    return $user;
  }

  public function modifyPassword(Request $request) {
    // Input
    $idUser = $request->id;
    $password = $request->password;
    $newPassword = $request->newPassword;
    $rePassword = $request->rePassword;

    // Process
    if (!isset($password) || !isset($newPassword) || !isset($rePassword)) {
      return response()->json(['error' => __("validation.required_password")], 500);
    }
    if ($newPassword !== $rePassword) {
      return response()->json(['error' => __("validation.password_not_matched")], 500);
    }

    $passwordEncrypted = WpPassword::make($newPassword);

    $struct = [
      'user_pass' => $passwordEncrypted,
    ];

    $user = User::where('ID', '=', $idUser)->update($struct);

    // Response
    return $user;
  }
}
