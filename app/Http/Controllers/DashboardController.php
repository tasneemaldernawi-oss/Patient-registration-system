<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
// to work with date and time
use Carbon\Carbon;

class DashboardController extends Controller
{
    //

    public function index(){
        // Check if admin is logged in
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }

         $dailyLoad = Appointment::whereDate('date', Carbon::today())->count();
         $totalPatients = Patient::count();
         $pendingAppointments = Appointment::where('status', 'pending')->count();
         $speciallistCount = Doctor::count();
         // fetching the daily schedule
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
}
