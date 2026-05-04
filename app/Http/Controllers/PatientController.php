<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // show all patients
    public function index(){
        $patients = Patient::latest()->get();
        return view('patients.index', compact('patients'));

    }

    // adding patients

    public function create(){
        return view('patients.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:patients,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')
                         ->with('success', 'Patient registered successfully!');
        
    }
     //we use (Patient $patient) when we deal with one specific row
    public function edit(Patient $patient){
        return view('patients.edit', compact('patients'));
    }

    public function update(Request $request, Patient $patient){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'reqired|email|unique:patients,email' . $patient->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
        ]);

        $patient->update($validated);
        return redirect()->route('patients.index')->with('success', 'Patient updates successfully');
    }

    public function destroy(Patient $patient){
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient updated successfully');
    }

}
