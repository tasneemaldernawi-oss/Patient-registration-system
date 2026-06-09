<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Specialty;

class DoctorController extends Controller
{
    //
    public function index(){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }

        $doctors = Doctor::latest()->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create() {
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
        $specialties = Specialty::all();
        return view('admin.doctors.create', compact('specialties'));
    }

    public function store(Request $request){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,',
            'specialty_id' => 'required|exists:specialties,id',
            'speciality' => 'nullable|string|max:500',
            'experience' => 'required|string|max:500',
            'address' => 'nullable|string|max:500',
        ], [
            'email.unique'=> 'That doctor already exist in out records.',
            'specialty_id.required' => 'Please select a specialty.',
            'specialty_id.exists' => 'The selected specialty is invalid.',
        ]);
        \App\Models\Doctor::create($validated);

        return redirect()->route('doctors.index')
        ->with('success', 'Doctor added successfully!');
    }

    public function edit(Doctor $doctor){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
        $specialties = Specialty::all();
        return view ('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request, Doctor $doctor){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'specialty_id' => 'required|exists:specialties,id',
            'speciality' => 'nullable|string|max:500',
            'experience' => 'required|string|max:500',
            'address' => 'nullable|string|max:500',
        ], [
            'specialty_id.required' => 'Please select a specialty.',
            'specialty_id.exists' => 'The selected specialty is invalid.',
        ]);
        $doctor->update($validated);
        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully!');
    }

    public function destroy(Doctor $doctor){
        if (!session('is_logged_in') || !session('admin_id')) {
        return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
    }
 
       $doctor->delete();
       return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully');
    }


}
