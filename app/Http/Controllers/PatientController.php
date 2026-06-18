<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Patient;
class PatientController extends Controller
{
    // show all patients
    public function index(){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }

        $patients = Patient::latest()->paginate(10);
        return view('admin.patients.index', compact('patients'));

    }

    // adding patients

    public function create(){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }

        return view('admin.patients.create');
    }

    public function store(Request $request){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:patients,email',
            'gender' => 'required|in:Male,Female',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string|max:500',
        ], [
            'email.unique' => 'That patient already exists in our records.',
        ]
        
        );
        
        $patient = \App\Models\Patient::create($validated);

        // Return JSON for AJAX requests (used by booking flow)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($patient, 201);
        }

        return redirect()->route('patients.index')
                         ->with('success', 'Patient registered successfully!');
        
    }
     //we use (Patient $patient) when we deal with one specific row
    public function edit(Patient $patient){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }

        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            //ignore current email
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'gender' => 'required|in:Male,Female',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string|max:500',
        ]);

        $patient->update($validated);
        return redirect()->route('patients.index')->with('success', 'Patient updates successfully');
    }

    public function destroy(Patient $patient){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
    
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully');
    }

}
