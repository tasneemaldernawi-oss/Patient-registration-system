<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index(){
        $appointments = Appointment::latest()->paginate(10);
        return view('admin.appointments.index', compact('appointments'));
    }
    public function create(){
        $doctors = Doctor::all();
        $patients = Patient::all();

        return view('admin.appointments.create', compact('doctors', 'patients'));
    }
    public function store(Request $request){
        $validated =$request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'reason' => 'required|string|max:1000',
        ]);

        \App\Models\Appointment::create($validated + ['status' => 'pending']);
        return redirect()->route('appointments.index')
          ->with('success', 'Appointment booked successfully!');

    }
    public function destory(Appointment $appointment){
        $appointment->delete();
        return redirect()->route('appointment.index')->with('success', 'Appointment deleted sucessfullt');
    }
}
