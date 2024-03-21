<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationtypeController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\MedicineController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => 'auth:api'], function () {


    //CONSULTATION
    Route::get('consultation', [ConsultationController::class, 'index']);
    Route::get('consultation/Invoice/{id}', [ConsultationController::class, 'indexConsultationInvoice']);
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

    //FACTURA
    Route::get('invoice', [InvoiceController::class, 'index']);
    Route::get('invoice/{id}', [InvoiceController::class, 'show']);
    Route::post('invoice', [InvoiceController::class, 'store']);
    Route::put('invoice/{id}', [InvoiceController::class, 'update']);
    Route::delete('invoice/{id}', [InvoiceController::class, 'destroy']);

    //MEDICINE
    Route::get('medicine', [MedicineController::class, 'index']);
    Route::get('medicine/{id}', [MedicineController::class, 'show']);
    Route::post('medicine', [MedicineController::class, 'store']);
    Route::put('medicine/{id}', [MedicineController::class, 'update']);
    Route::delete('medicine/{id}', [MedicineController::class, 'destroy']);

    //MEDICO
    Route::get('medico', [MedicoController::class, 'index']);
    Route::get('medico/{id}', [MedicoController::class, 'show']);
    Route::post('medico', [MedicoController::class, 'store']);
    Route::put('medico/{id}', [MedicoController::class, 'update']);
    Route::delete('medico/{id}', [MedicoController::class, 'destroy']);

    //PRESCRIPTION
    Route::get('prescription', [PrescriptionController::class, 'index']);
    Route::get('prescriptionConsultation/{id}', [PrescriptionController::class, 'showPrescription']);
    Route::get('prescription/{id}', [PrescriptionController::class, 'show']);
    Route::post('prescription', [PrescriptionController::class, 'store']);
    Route::put('prescription/{id}', [PrescriptionController::class, 'update']);
    Route::delete('prescription/{id}', [PrescriptionController::class, 'destroy']);

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('userProfile', [AuthController::class, 'userProfile']);
});

//SPECIALITY
Route::get('speciality', [SpecialityController::class, 'index']);
Route::get('speciality/{id}', [SpecialityController::class, 'show']);
Route::post('speciality', [SpecialityController::class, 'store']);
Route::put('speciality/{id}', [SpecialityController::class, 'update']);
Route::delete('speciality/{id}', [SpecialityController::class, 'destroy']);


Route::post('register', [AuthController::class, 'register']);
Route::get('doctor', [AuthController::class, 'doctor']);
Route::get('pacient', [AuthController::class, 'pacient']);

Route::post('login', [AuthController::class, 'login']);

Route::get('users', [AuthController::class, 'allUsers']);

Route::get('epsPublic', [EpsController::class, 'indexPublic']);
