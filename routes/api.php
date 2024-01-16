<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\PersonController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
    
    //EPS
    Route::get('eps', [EpsController::class, 'index']);
    Route::get('eps/{id}', [EpsController::class, 'show']);
    Route::post('eps', [EpsController::class, 'store']);
    Route::put('eps/{id}', [EpsController::class, 'update']);
    Route::delete('eps/{id}', [EpsController::class, 'destroy']);

});

Route::apiResource("v1/persons", PersonController::class);

Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::get('users', [AuthController::class, 'allUsers']);

Route::get('epsPublic', [EpsController::class, 'indexPublic']);
