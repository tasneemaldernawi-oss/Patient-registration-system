<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Patient;
class PatientController extends Controller
{
    // show all patients
    public function index(){
        $patients = Patient::latest()->paginate(10);
        return view('admin.patients.index', compact('patients'));

    }

    // adding patients

    public function create(){
        return view('admin.patients.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:patients,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string|max:500',
        ], [
            'email.unique' => 'That patient already exists in our records.',
        ]
        
        );
        
        \App\Models\Patient::create($validated);

        return redirect()->route('patients.index')
                         ->with('success', 'Patient registered successfully!');
        
    }
     //we use (Patient $patient) when we deal with one specific row
    public function edit(Patient $patient){
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            //ignore current email
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string|max:500',
        ]);

        $patient->update($validated);
        return redirect()->route('patients.index')->with('success', 'Patient updates successfully');
    }

    public function destroy(Patient $patient){
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient updated successfully');
    }

}
