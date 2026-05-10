<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Controllers\PatientController;
use Illuminate\Http\Controllers\DoctorController;

Route::get('/', function () {
    return view('welcome');
});

// create group for admin access
Route::prefix('admin')->group(function(){
    if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->is_admin){
        Route::resource('patients', PatientController::class);
        Route::resource('doctors', DoctorController::class);
    } else {
        Route::any('{any}', function (){
            return abort(403, 'Admin access required');
        })->where('any', '*');
    }
});