<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::latest()->paginate(10);
        $specialties = Specialty::all();
        $patients = Patient::all();
        $doctors = Doctor::all();
        
        return view('receptionist.appointments.index', compact('appointments', 'specialties', 'patients', 'doctors'));
    }

    public function create()
    {
        // Fetch doctors and patients for dropdowns
        $doctors = Doctor::all();
        $patients = Patient::all();

        return view('receptionist.appointments.create', compact('doctors', 'patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'date'       => 'required|date|after_or_equal:today',
            'time'       => 'required',
            'status'     => 'required|in:pending,confirmed,cancelled',
            'reason'     => 'nullable|string|max:1000',
        ]);

        Appointment::create($validated);
        
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment booked successfully!');
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();

        return view('receptionist.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
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

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully');
    }
}