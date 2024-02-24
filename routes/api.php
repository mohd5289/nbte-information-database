<?php

use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\ProgrammesController;
use App\Http\Controllers\saveDirectoryProgrammesController;
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
Route::get('/all-institutions-and-programmes', [saveDirectoryProgrammesController::class, 'getAllInstitutionsAndProgrammes']);
// Route::get('/save-one-institution', [saveDirectoryProgrammesController::class, 'storeFirstProgramme']);

// Route::get('/save-remaining-institutions',[saveDirectoryProgrammesController::class, 'storeRemainingProgrammesInAuchi']);

// Route::get('/save-kadpoly-institutions', [saveDirectoryProgrammesController::class, 'saveKadunaPolytechnicProgrammes']);


// Route::get('/save-fedPolyNekedeProgrammes',[saveDirectoryProgrammesController::class,'saveNekedePolyProgrammes']);

// Route::get('/save-fedPolyBauchi-programmes',[saveDirectoryProgrammesController::class,'saveFedPolyBauchiProgrammes']);