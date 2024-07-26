<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;

use Illuminate\Http\Request;

use WpPassword;
use Session;
use Cookie;

class AuthController extends Controller {
  public function login(Request $request) {
    $request->validate([
      "email" => "required",
      "password" => "required",
    ], [
      "email.required" => __("validation.required"),
      "password.required" => __("validation.required_password"),
    ]);

    $email = $request->email;
    $password = $request->password;

    $user = User::where("user_email", "=", $email)
      ->orWhere('user_login', '=', $request->email)
      ->first();
    if ($user === null) {
      return back()->with("fail", __("validation.account"));
    }
    if ($user->user_status < 1) {
      return back()->with("fail", __("validation.account_inactive"));
    }
    $passwordDB = $user->user_pass;

    $isCorrect = WpPassword::check($password, $passwordDB);
    if (!$isCorrect) {
      return back()->with("fail", __("validation.password"));
    }

    $courses = Subscription::select(
        "COURSE.code"
      )
      ->join("COURSE", "COURSE.id", "=", "SUBSCRIPTION.courseId")
      ->where("userId", "=", $user->ID)
      ->get();
    $strCourses = "";
    foreach ($courses as $course) {
      $strCourses = $strCourses . "," . $course->code;
    }

    $request->session()->put("loginId", $user->ID);
    Cookie::queue('user_fullname', "{$user->display_name}", 1000);
    Cookie::queue('user_avatar', "$user->avatar", 1000);
    Cookie::queue('user_courses', "$strCourses", 1000);

    return redirect("home");
  }

  public function logout() {
    if (Session::has("loginId")) {
      Session::pull("loginId");
      $cookie = Cookie::forget('user_fullname'); // Doesn't appears work !!!
      $cookie = Cookie::forget('user_avatar');
      return redirect("/")->cookie($cookie);
    }
  }
}
