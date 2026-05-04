<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Controllers\PatientController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('patients', PatientController::class);