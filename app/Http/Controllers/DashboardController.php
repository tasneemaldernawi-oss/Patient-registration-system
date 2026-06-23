<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return $this->adminMetrics();
        }

        if ($role === 'receptionist') {
            return $this->receptionistMetrics();
        }
        
        if ($role === 'doctor') {
            return $this->myDepartmentPatients();
        }
        return redirect('/login');
    }

    protected function adminMetrics()
    {
        $dailyLoad = Appointment::whereDate('date', Carbon::today())->count();
        $totalPatients = Patient::count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        $speciallistCount = User::where('role', 'doctor')->count();

        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('date', Carbon::today())
            ->orderBy('time', 'asc')
            ->get();
            
        $recentPatients = Patient::latest()->take(3)->get();

        return view('admin.dashboard', compact(
            'dailyLoad',
            'totalPatients',
            'pendingAppointments',
            'speciallistCount',
            'appointments',
            'recentPatients'
        ));
    }

    protected function receptionistMetrics()
    {
        
        $totalPatients = Patient::count();

        
        $appointments = Appointment::with(['patient', 'doctor.specialtyProfile'])
            ->whereDate('date', Carbon::today())
            ->orderBy('time', 'asc')
            ->get();

        $appointmentsBySpeciality = $appointments->groupBy(function ($appointment) {
            return $appointment->doctor && $appointment->doctor->specialtyProfile 
                ? $appointment->doctor->specialtyProfile->name 
                : 'General/Unassigned';
        });

        
        $patients = Patient::latest()->take(5)->get();
        $todayAppointmentsCount = $appointments->count();
        $pendingCount = Appointment::where('status', 'pending')->count();

        
        return view('receptionist.dashboard', compact(
            'totalPatients',
            'appointmentsBySpeciality',
            'patients', 
            'todayAppointmentsCount', 
            'pendingCount'
        ));
    }


    public function myDepartmentPatients()
    {
        
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('date', Carbon::today())
            ->orderBy('time', 'asc')
            ->get();

      
        return view('doctor.dashboard', compact('appointments'));
    }
}