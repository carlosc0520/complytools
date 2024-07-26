<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestoreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NegativeListsController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\ScoringController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
  return view("welcome");
})->name("welcome")->middleware("guest");

Route::post("/login", [AuthController::class, "login"])->name("login");
Route::get("/logout", [AuthController::class, "logout"])->name("logout");

Route::get("/restore", [RestoreController::class, "view"])->name("restore")->middleware("guest");
Route::post("/restore-password", [RestoreController::class, "restore"])->name("restore-password");

Route::get("/home", HomeController::class)->name("home")->middleware('auth');
Route::get("/profile", ProfileController::class)->name("profile")->middleware('auth');

Route::get("/negativelists", [NegativeListsController::class, "viewList"])->name("negativelists")->middleware('auth');
Route::post('/negativelists/massive', [NegativeListsController::class, "massive"])->name("negativelists.massive");
Route::get("/negativelists-searches", [NegativeListsController::class, "viewListSearches"])->name("negativelists.searches")->middleware('auth');
Route::get("/negativelists-admin", [NegativeListsController::class, "viewAdmin"])->name("negativelists.admin")->middleware('auth');

Route::get("/risks", [RiskController::class, "viewList"])->name("risks")->middleware('auth');
Route::get("/risks/generate", [RiskController::class, "viewGenerate"])->name("risk.generate")->middleware("auth");
Route::get("/risks/details/{id}", [RiskController::class, "viewDetails"])->name("risk")->middleware("auth");

Route::get("/scoring", [ScoringController::class, "viewList"])->name("scoring")->middleware('auth');
Route::get("/scoring/generate/natural", [ScoringController::class, "viewGenerateNatural"])->name("scoring.generate.natural")->middleware("auth");
Route::get("/scoring/generate/company", [ScoringController::class, "viewGenerateCompany"])->name("scoring.generate.company")->middleware("auth");
Route::get("/scoring/details/natural/{id}", [ScoringController::class, "viewDetailsNatural"])->name("scoring.details.natural")->middleware("auth");
Route::get("/scoring/details/company/{id}", [ScoringController::class, "viewDetailsCompany"])->name("scoring.details.company")->middleware("auth");
Route::post('/scoring/massive-natural', [ScoringController::class, "massiveNatural"])->name("scoring.massive.natural");
Route::post('/scoring/massive-company', [ScoringController::class, "massiveCompany"])->name("scoring.massive.company");

Route::get("/complaints", [ComplaintController::class, "viewList"])->name("complaints")->middleware('auth');

Route::get("/operations", [OperationController::class, "viewList"])->name("operations")->middleware('auth');
Route::get("/operations/generate", [OperationController::class, "viewGenerate"])->name("operation.generate")->middleware("auth");
Route::get("/operations/details/{id}", [OperationController::class, "viewDetails"])->name("operation")->middleware("auth");
Route::post('/operations/massive', [OperationController::class, "massive"])->name("operation.massive");

Route::get("/reports", [ReportController::class, "viewList"])->name("reports")->middleware('auth');
Route::get("/reports/generate", [ReportController::class, "viewGenerate"])->name("report.generate")->middleware("auth");
Route::get("/reports/details/{id}", [ReportController::class, "viewDetails"])->name("report")->middleware("auth");