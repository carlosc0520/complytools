<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use Mail;
use App\Mail\NotifyMail;
use WpPassword;

class RestoreController extends Controller {
  public $success = 0;
  public $captcha = 0;
  public $sitekey = "";

  public function view() {
    $this->sitekey = config('captcha.sitekey');
    return view("restore", [
      "success" => $this->success,
      "captcha" => $this->captcha,
      "sitekey" => $this->sitekey,
    ]);
  }

  public function restore(Request $request) {
    $request->validate([
      "email" => "required",
      "g-recaptcha-response" => "required|captcha",
    ], [
      "email.required" => __("validation.required"),
      "g-recaptcha-response.required" => __("validation.required_captcha"),
    ]);

    $email = $request->email;
    $secret = config('captcha.secret');
    $captcha = $request['g-recaptcha-response'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=$secret&response=$captcha");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $response = json_decode($result, TRUE);
    curl_close($ch);
    if ($response['success'] == false) {
      $captcha = -1;
    }

    $temporaryPassword = Str::random(16);
    $temporaryPassword_hashed = WpPassword::make($temporaryPassword);

    User::where('user_email', '=', $request->email)->update(
      [ "user_pass" => $temporaryPassword_hashed ]
    );

    $details = [
      "temporaryPassword" => $temporaryPassword,
      "link" => env('APP_URL', '#'),
    ];
    $success = 1;
    $captcha = 1;

    Mail::to($email)->send(new NotifyMail($details));
    if (Mail::failures()) {
      // return response()->Fail("Sorry! Please try again latter");
      $success = -1;
    }

    // return response()->json("Great! Success");
    // return view("pages.restore", compact("success"));
    return view("restore", [
      "success" => $success,
      "captcha" => $captcha,
      "sitekey" => $this->sitekey,
    ]);
  }
}
