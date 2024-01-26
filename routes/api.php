<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationtypeController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PersonController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => 'auth:api'], function () {

    
    //CONSULTATION
    Route::get('consultation', [ConsultationController::class, 'index']);
    Route::get('consultation/{id}', [ConsultationController::class, 'show']);
    Route::post('consultation', [ConsultationController::class, 'store']);
    Route::put('consultation/{id}', [ConsultationController::class, 'update']);
    Route::delete('consultation/{id}', [ConsultationController::class, 'destroy']);
    
    //CONSULTATION_TYPE
    Route::get('consultationType', [ConsultationtypeController::class, 'index']);
    Route::get('consultationType/{id}', [ConsultationtypeController::class, 'show']);
    Route::post('consultationType', [ConsultationtypeController::class, 'store']);
    Route::put('consultationType/{id}', [ConsultationtypeController::class, 'update']);
    Route::delete('consultationType/{id}', [ConsultationtypeController::class, 'destroy']);

    //EPS
    Route::get('eps', [EpsController::class, 'index']);
    Route::get('eps/{id}', [EpsController::class, 'show']);
    Route::post('eps', [EpsController::class, 'store']);
    Route::put('eps/{id}', [EpsController::class, 'update']);
    Route::delete('eps/{id}', [EpsController::class, 'destroy']);

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('userProfile', [AuthController::class, 'userProfile']);
});

Route::apiResource("v1/persons", PersonController::class);

Route::post('register', [AuthController::class, 'register']);
Route::get('personDoctor', [AuthController::class, 'indexPersonDoctor']);
Route::get('personPatient', [AuthController::class, 'indexPersonPatient']);

Route::post('login', [AuthController::class, 'login']);

Route::get('users', [AuthController::class, 'allUsers']);

Route::get('epsPublic', [EpsController::class, 'indexPublic']);

//MEDICO
Route::get('medico', [MedicoController::class, 'index']);
Route::get('medico/{id}', [MedicoController::class, 'show']);
Route::post('medico', [MedicoController::class, 'store']);
Route::put('medico/{id}', [MedicoController::class, 'update']);
Route::delete('medico/{id}', [MedicoController::class, 'destroy']);
