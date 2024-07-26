<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Course;

use Session;

class HomeController extends Controller {
  public function __invoke() {
    $isLogged = Session::has("loginId");
    if (!$isLogged) {
      return redirect("/");
    }

    $userId = Session::get("loginId");

    // $items = config('constants.sections');
    $items = Course::selectRaw("
        COURSE.id,
        COURSE.icon_path as icon,
        COURSE.url,
        COURSE.name as title,
        (select SUBSCRIPTION.userId from SUBSCRIPTION where SUBSCRIPTION.courseId = COURSE.id and SUBSCRIPTION.userId=".$userId.") as isActive
      ")
      ->get();

    return view("pages.home", compact('items'));
  }
}
