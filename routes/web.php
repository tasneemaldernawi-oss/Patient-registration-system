<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


// create group for admin access
Route::prefix('admin')->group(function(){
    Route::resource('patients', PatientController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('appointments', AppointmentController::class);
   /* if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->is_admin){
        Route::resource('patients', PatientController::class);
        Route::resource('doctors', DoctorController::class);
    } else {
        Route::any('{any}', function (){
            return abort(403, 'Admin access required');
        })->where('any', '*');
    } */
});