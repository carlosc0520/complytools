<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NegativeListsController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\ScoringController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ProgramadaController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
  Route::prefix('/common')->group(function() {
    Route::controller(CommonController::class)->group(function () {
      Route::get('/list-areas-by-user/{userId}', 'listAreasByUser');
      Route::get('/list-processes-by-area/{areaId}', 'listProcessesByArea');
      Route::get('/list-ocupations', 'listOcupations');
      Route::get('/list-obligations', 'listObligations');
      Route::get('/list-cius', 'listCius');
      Route::get('/list-scstatus', 'listScStatus');
      Route::get('/list-compositions', 'listCompositions');
      Route::get('/list-sensibles', 'listSensibles');
      Route::get('/list-peps', 'listPeps');
      Route::get('/list-countries', 'listCountries');
      Route::get('/list-offices', 'listOffices');
      Route::get('/list-products', 'listProducts');
      Route::get('/list-currencies', 'listCurrencies');
      Route::get('/list-fundings', 'listFundings');
      Route::get('/list-companytypes', 'listCompanyTypes');
      Route::get('/list-companysizes', 'listCompanySizes');
      Route::get('/list-scoring-natural-commons', 'listScoringNaturalCommons');
      Route::get('/list-scoring-company-commons', 'listScoringCompanyCommons');
      Route::get('/list-report-commons', 'listReportCommons');
    });
  });

  Route::prefix('/profile')->group(function() {
    Route::controller(ProfileController::class)->group(function () {
      Route::post('/upload-avatar', 'uploadAvatar');
      Route::put('/modify-password', 'modifyPassword');
    });
  });

  Route::prefix('/company')->group(function() {
    Route::controller(CompanyController::class)->group(function () {
      Route::get('/users/{iduser}', 'listUsers');
    });
  });

  Route::prefix('/negativelists')->group(function() {
    Route::controller(NegativeListsController::class)->group(function () {
      Route::get('/get-counter/{iduser}', 'getCounter');
      Route::post('/list-datatable', 'dtTable');
      Route::get('/list-usersNotify', 'mailMasivo');
      Route::post('/list-datatable-search', 'dtTableSearch');
      Route::get('/details/object/{iduser}/{id}/{issearch}', 'details');
      Route::get('/details/pdf/{iduser}/{id}', 'detailsPDF');
      Route::get('/details/pdf-empty/{iduser}/{search}', 'detailsPDFEmpty');
      Route::get('/searches/{iduser}', 'searchesByCompany');
      Route::post("/massive-admin", "massiveAdmin");
      Route::post('/assign', 'assign');
    });
  });

  Route::prefix('/programada')->group(function() {
    Route::controller(ProgramadaController::class)->group(function () {
      Route::post('/add-Programada', 'addProgramada');
      Route::get('/listProgramadas/{iduser}', 'listProgramadas');
      Route::delete('/deleteProgramada/{iduser}', 'deleteProgramada');
      Route::get('/notifyProgramada', 'notifyProgramada');
    });
  });

  Route::prefix('/risk')->group(function() {
    Route::controller(RiskController::class)->group(function () {
      Route::get('/list/{type}/{userId}', 'list');
      Route::post('/list-datatable', 'dtTable');
      Route::get('/details/pdf/{iduser}/{id}', 'detailsPDF');
      Route::post('/create', 'create');
      Route::post('/modify', 'modify');
      Route::post('/upload-file', 'uploadFile');
      Route::post('/assign', 'assign');
      Route::post('/create-process', 'createProcess');
      Route::post('/create-area', 'createArea');
      Route::delete('/delete-process/{id}', 'deleteProcess');
      Route::delete('/delete-area/{id}', 'deleteArea');
      Route::get('/list-processes-by-area/{areaId}', 'listProcessesByArea');
      Route::post('/create-areaprocesses', 'createAreaProcesses');
    });
  });

  Route::prefix('/scoring')->group(function() {
    Route::controller(ScoringController::class)->group(function () {
      Route::post('/list-datatable', 'dtTable');
      Route::get('/list/{iduser}', 'list');
      Route::get('/details/pdf-natural/{iduser}/{id}', 'detailsPDFNatural');
      Route::get('/details/pdf-company/{iduser}/{id}', 'detailsPDFCompany');
      Route::post('/create-natural', 'createNatural');
      Route::post('/create-company', 'createCompany');
      Route::put('/modify-natural', 'modifyNatural');
      Route::put('/modify-company', 'modifyCompany');
      Route::post('/assign', 'assign');
    });
  });

  Route::prefix('/complaint')->group(function() {
    Route::controller(ComplaintController::class)->group(function () {
      Route::post('/list-datatable', 'dtTable');
      Route::get('/details/object/{id}', 'details');
      Route::get('/details/pdf/{iduser}/{id}', 'detailsPDF');
      Route::post('/historial', 'createMessage');
      Route::post('/set-expiration-date', 'setExpirationDate');
      Route::post('/close', 'close');
      Route::post('/close-incomplete', 'closeIncomplete');
      Route::get('/team/{iduser}/{complaintid}', 'team');
      Route::put('/team', 'assign');
    });
  });

  Route::prefix('/operation')->group(function() {
    Route::controller(OperationController::class)->group(function () {
      Route::post('/list-datatable', 'dtTable');
      Route::get('/details/excel/{id}', 'export');
      Route::post('/create', 'create');
      Route::post('/create-list-beneficiaries', 'createListBeneficiaries');
      Route::put('/modify', 'modify');
      Route::post('/assign', 'assign');
    });
  });

  Route::prefix('/report')->group(function() {
    Route::controller(ReportController::class)->group(function () {
      Route::post('/list-datatable', 'dtTable');
      Route::get('/details/excel/{id}', 'export');
      Route::post('/create', 'create');
      Route::post('/create-list-people', 'createListPeople');
      Route::put('/modify', 'modify');
      Route::post('/assign', 'assign');
    });
  });
});