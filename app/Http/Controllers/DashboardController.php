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
        // 1. Get the authenticated user's role
        $role = Auth::user()->role;

        // 2. Route the user to their specific dashboard metrics layout
        if ($role === 'admin') {
            return $this->adminMetrics();
        }

        if ($role === 'receptionist') {
            return $this->receptionistMetrics();
        }
        
        // Add routing fallback for doctor if they hit the base dashboard index route
        if ($role === 'doctor') {
            return $this->myDepartmentPatients();
        }

        // Fallback safety redirect
        return redirect('/login');
    }

    /**
     * Compile metrics strictly for the System Administrator
     */
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

    /**
     * Compile metrics strictly for the Front-Desk Receptionist
     */
    protected function receptionistMetrics()
    {
        // Fetch today's actual appointment models so the view loop can render patient cards cleanly
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('date', Carbon::today())
            ->orderBy('time', 'asc')
            ->get();

        // Receptionists care about today's scheduling flow and quick glance data
        $patients = Patient::latest()->take(5)->get();
        $todayAppointmentsCount = $appointments->count(); // Optimized: counts the collection in memory instead of hitting DB again
        $pendingCount = Appointment::where('status', 'pending')->count();

        // Renders resources/views/receptionist/dashboard.blade.php cleanly with all expected variables
        return view('receptionist.dashboard', compact(
            'appointments',
            'patients', 
            'todayAppointmentsCount', 
            'pendingCount'
        ));
    }

    /**
     * Compile metrics strictly for the logged-in Doctor
     * This matches your route: Route::get('/dashboard', [DashboardController::class, 'myDepartmentPatients'])
     */
    public function myDepartmentPatients()
    {
        // Fetch today's appointments for this doctor's specific department
        // Note: Since 'department' is not directly on the user table right now, 
        // we pull all today's appointments. If you filter by specialty later, you can add .where() checks here.
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('date', Carbon::today())
            ->orderBy('time', 'asc')
            ->get();

        // Renders resources/views/doctor/dashboard.blade.php cleanly
        return view('doctor.dashboard', compact('appointments'));
    }
}