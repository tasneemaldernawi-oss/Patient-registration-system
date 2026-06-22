<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::latest()->paginate(10);
        return view('receptionist.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('receptionist.patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|unique:patients,email',
            'gender'        => 'required|in:Male,Female',
            'phone'         => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'address'       => 'nullable|string|max:500',
        ]);
        
        $patient = Patient::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($patient, 201);
        }

        return redirect()->route('patients.index')->with('success', 'Patient registered successfully!');
    }

    public function edit(Patient $patient)
    {
        return view('receptionist.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:patients,email,' . $patient->id,
            'gender'        => 'required|in:Male,Female',
            'phone'         => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'address'       => 'nullable|string|max:500',
        ]);

        $patient->update($validated);
        return redirect()->route('patients.index')->with('success', 'Patient metrics updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient record discarded.');
    }
}