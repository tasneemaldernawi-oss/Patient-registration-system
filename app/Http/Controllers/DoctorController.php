<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
 
    public function index()
    {
        $doctors = Doctor::with('specialtyProfile')->latest()->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specialties = Specialty::all();
        return view('admin.doctors.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email'        => 'required|email|unique:users,email',
            'specialty_id' => 'required|exists:specialties,id',
    
        ]);

        // 1. Create Login Credential profile in users table
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make('TemporaryPassword123!'), // Provide a temporary password
            'role'     => 'doctor',
        ]);

        // 2. Create the associated Profile linking them together
        $doctor = Doctor::create([
            'user_id'      => $user->id,
            'name'         => $validated['name'],
            'specialty_id' => $validated['specialty_id'],
            'speciality'   => Specialty::find($validated['specialty_id'])->name, 
            'phone_number' => $validated['phone_number'],
        ]);

        if ($request->has('schedules')){
            foreach ($request->schedules as $schedule) {
                $doctor->schedules()->create($schedule);
            }
        }
        return redirect()->route('doctors.index')->with('success', 'Doctor and User credentials created successfully!');
    }

    public function edit(Doctor $doctor)
    {
        $specialties = Specialty::all();
        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'specialty_id' => 'required|exists:specialties,id',
            'phone_number' => 'nullable|string|max:20',
           
        ]);

        // Sync updates to both the User entity and the Doctor Profile
        $doctor->user->update([
            'name'  => $validated['name'],
            'email' => $validated['email']
        ]);

        $doctor->update([
           'name'         => $validated['name'],
           'specialty_id' => $validated['specialty_id'],
           'speciality'   => Specialty::find($validated['specialty_id'])->name,
           'phone_number' => $validated['phone_number'],
        ]);

        $doctor->schedules()->delete();
        if ($request->has('schedules')) {
        foreach ($request->schedules as $schedule) {
            $doctor->schedules()->create($schedule);
            }
        }

        return redirect()->route('doctors.index')->with('success', 'Doctor details synchronized.');
    }

    public function destroy(Doctor $doctor)
    {
        
        User::destroy($doctor->user_id); 
        return redirect()->route('doctors.index')->with('success', 'Doctor removed from active logs.');
    }

    public function myDepartmentPatients()
    {
        $doctorUser = Auth::user();

        // Safety verification in case the doctor profile hasn't been instantiated yet
        if (!$doctorUser->doctorProfile) {
            abort(404, 'Structural Doctor profile missing.');
        }

        $specialtyId = $doctorUser->doctorProfile->specialty_id;

        // Pull appointments matching the authenticated doctor's department specialty ID
        $appointments = Appointment::whereHas('doctor', function($query) use ($specialtyId) {
                $query->where('specialty_id', $specialtyId);
            })
            ->with('patient')
            ->latest()
            ->get();

        return view('doctor.dashboard', compact('appointments'));
    }

    public function addDiagnosis(Request $request, $patientId)
    {
        $request->validate([
            'diagnosis'    => 'required|string',
            'medical_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB limit
        ]);

        $filePath = null;
        if ($request->hasFile('medical_file')) {
            $filePath = $request->file('medical_file')->store('patient_records', 'public');
        }

        MedicalRecord::create([
            'patient_id' => $patientId,
            'doctor_id'  => Auth::id(), // Assigned to the logged-in user
            'diagnosis'  => $request->diagnosis,
            'file_path'  => $filePath,
        ]);

        return redirect()->back()->with('success', 'Medical file updated successfully.');
    }
}