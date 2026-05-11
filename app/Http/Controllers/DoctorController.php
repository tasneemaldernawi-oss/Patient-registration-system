<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    //
    public function index(){
        $doctors = Doctor::latest()->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create() {
        return view('admin.doctors.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,',
            'speciality' => 'required|string|max:500',
            'experience' => 'required|string|max:500',
            'address' => 'nullable|string|max:500',

        ], [
            'email.unique'=> 'That doctor already exist in out records.',
        ]);
        \App\Models\Doctor::create($validated);

        return redirect()->route('doctors.index')
        ->with('success', 'Doctor added successfully!');
    }

    public function edit(Doctor $doctor){
        return view ('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'speciality' => 'required|string|max:500',
            'experience' => 'required|string|max:500',
            'address' => 'nullable|string|max:500',
        ]);
        $doctor->update($validated);
        return redirect()->route('doctors.index')->with('successs', 'Doctor updated successfully!');
    }

    public function destroy(Doctor $doctor){
 
       $doctor->delete();
       return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully');
    }


}
