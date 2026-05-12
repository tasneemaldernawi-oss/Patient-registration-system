<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index(){
        // Check if admin is logged in
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
    
        $appointments = Appointment::latest()->paginate(10);
        return view('admin.appointments.index', compact('appointments'));
    }
    public function create(){

        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
        }

        // Fetch doctors and patients for dropdowns
        $doctors = Doctor::all();
        $patients = Patient::all();

        return view('admin.appointments.create', compact('doctors', 'patients'));
    }
    public function store(Request $request){
        // Check if admin is logged in
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
        $validated =$request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled',
            'reason' => 'nullable|string|max:1000',
        ]);

        \App\Models\Appointment::create($validated + ['status' => 'pending']);
        return redirect()->route('appointments.index')
          ->with('success', 'Appointment booked successfully!');

    }

    public function edit(Appointment $appointment){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
        $patients = Patient::all();
        $doctors = Doctor::all();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
        $validated = $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'doctor_id'  => 'required|exists:doctors,id',
        'date'       => 'required|date',
        'time'       => 'required',
        'status'     => 'required|in:pending,confirmed,cancelled',
        'reason'     => 'nullable|string',
            
        ]);
        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully');
    }
    public function destroy(Appointment $appointment){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
    
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted sucessfullt');
    }
}
