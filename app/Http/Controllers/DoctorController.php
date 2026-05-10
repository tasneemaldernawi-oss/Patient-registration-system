<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    //
    public function index(){
        $doctors = Doctor::latest()->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create() {
        return view('admin.doctors.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email' . $doctor->id,
            'speciality' => 'required|text',
            'experience' => 'required|text',
            'address' => 'required|text',

        ]);
        Doctor::create($validated);

        return redirect()->route('admin.doctors.index')
        ->with('success', 'Doctor added successfully!');
    }

    public function edit(Doctor $doctor){
        return view ('admin.doctors.edit', compact('doctors'));
    }

    public function update(Request $request, Doctor $doctor){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email' . $doctor->id,
            'speciality' => 'required|text',
            'experience' => 'required|text',
            'address' => 'required|text',
        ]);
        $doctor->update($validated);
        return redirect()->route('admin.doctors.index')->with('successs', 'Doctor updated successfully!');
    }

    public function destroy(Doctor $doctor){
 
       $doctor->delete();
       return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully');
    }


}
