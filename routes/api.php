<?php

use App\Http\Controllers\IEIsController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\MonotechnicProgrammesController;

use App\Http\Controllers\ProgrammesController;

use App\Http\Controllers\TechnicalCollegesController;
use App\Http\Controllers\VEIsController;
use App\Models\VEI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/create-institution-with-programmes', [ProgrammesController::class, 'createInstitutionWithProgrammes']);

Route::post('/create-monotechnic-institution-with-college-of-agriculture-programmes', [MonotechnicProgrammesController::class, 'createInstitutionWithCollegeOfAgricultureProgrammes']);
Route::post('/create-monotechnic-institution-with-college-of-health-sciences-programmes', [MonotechnicProgrammesController::class, 'createInstitutionWithCollegeOfHealthSciencesProgrammes']);
Route::post('/create-monotechnic-institution-with-specialized-institution-programmes', [MonotechnicProgrammesController::class, 'createInstitutionWithSpecialisedInstitutionProgrammes']);

Route::post('/create-technical-colleges-institution-with-programmes', [TechnicalCollegesController::class, 'createInstitutionWithProgrammes']);
Route::post('/create-iei-institution-with-programmes', [IEIsController::class, 'createInstitutionWithProgrammes']);
Route::post('/create-vei-institution-with-programmes', [VEIsController::class, 'createInstitutionWithProgrammes']);

Route::get('/all-institutions-and-programmes', [ProgrammesController::class, 'getAllInstitutionsAndProgrammes']);

Route::get('/all-monotechnic-institutions-and-college-of-agriculture-programmes', [MonotechnicProgrammesController::class, 'getAllInstitutionsAndCollegeOfAgricultureProgrammes']);
Route::get('/all-monotechnic-institutions-and-college-of-health-sciences-programmes', [MonotechnicProgrammesController::class, 'getAllInstitutionsAndCollegeOfHealthSciencesProgrammes']);
Route::get('/all-monotechnic-institutions-and-specialized-institution-programmes', [MonotechnicProgrammesController::class, 'getAllInstitutionsAndSpecialisedInstitutionProgrammes']);


Route::get('/all-technical-colleges-institutions-and-programmes', [TechnicalCollegesController::class, 'getAllInstitutionsAndProgrammes']);
Route::get('/all-iei-institutions-and-programmes', [IEIsController::class, 'getAllInstitutionsAndProgrammes']);
Route::get('/all-vei-institutions-and-programmes', [VEIsController::class, 'getAllInstitutionsAndProgrammes']);


Route::post('/all-institutions', [InstitutionController::class, 'saveAllInstitutions']);
Route::post('/all-programmes', [ProgrammesController::class,'addAllProgrammes']);
Route::get('/all-Programmes', [ProgrammesController::class, 'getAllProgrammes']);
// Route::get('/save-one-institution', [saveDirectoryProgrammesController::class, 'storeFirstProgramme']);
Route::get('/all-Institutions', [InstitutionController::class,'getAllInstitutions']);


Route::delete('/kanostatepoly-institution', [ProgrammesController::class, 'deleteKanoStatePolyInstitution']);

// Route::delete('/iei-institution', [IEIsController::class, 'deleteIEIInstitution']);
// Route::get('/save-remaining-institutions',[saveDirectoryProgrammesController::class, 'storeRemainingProgrammesInAuchi']);

// Route::get('/save-kadpoly-institutions', [saveDirectoryProgrammesController::class, 'saveKadunaPolytechnicProgrammes']);


// Route::get('/save-fedPolyNekedeProgrammes',[saveDirectoryProgrammesController::class,'saveNekedePolyProgrammes']);

// Route::get('/save-fedPolyBauchi-programmes',[saveDirectoryProgrammesController::class,'saveFedPolyBauchiProgrammes']);