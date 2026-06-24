<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;

// Public Guest Routes
Route::get('/', fn() => redirect('/login'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Shielded Application Groups
Route::middleware(['auth'])->group(function () {

    // ==========================================
    // 1. ADMIN ONLY ROUTES
    // ==========================================
    Route::middleware(['role:admin'])->prefix('admin')->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('doctors', DoctorController::class); 
    });

    // ==========================================
    // 2. RECEPTIONIST ONLY ROUTES
    // ==========================================
    Route::middleware(['role:receptionist'])->prefix('receptionist')->group(function() {
        // Points to PatientController so receptionists don't crash on admin stats
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('receptionist.dashboard');
        Route::resource('patients', PatientController::class);
        Route::resource('appointments', AppointmentController::class);
    });

    // ==========================================
    // 3. DOCTOR ONLY ROUTES (Dentist / Dermatology Hubs)
    // ==========================================
    Route::middleware(['role:doctor'])->prefix('doctor')->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'myDepartmentPatients'])->name('doctor.dashboard');
        Route::get('/patients/{patient}/record', [DoctorController::class, 'showPatientRecord'])->name('doctor.patient.record');
        Route::post('/patients/{patient}/medical-records', [DoctorController::class, 'addDiagnosis'])->name('doctor.add-diagnosis');
    });
});