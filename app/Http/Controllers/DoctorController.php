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
            'email' => $validated['email']
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
        $doctor->load('schedules'); 
        $schedules = \App\Models\DoctorSchedule::where('doctor_id', $doctor->id)->get();
        return view('admin.doctors.edit', compact('doctor', 'specialties', 'schedules'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $rules = [
        'name'         => 'required|string|max:255',
        'specialty_id' => 'required|exists:specialties,id',
        'phone_number' => 'nullable|string|max:20',
    ];
   
    if ($doctor->user) {
        
        $rules['email'] = 'required|email|unique:users,email,' . $doctor->user->id;
    } else {
       
        $rules['email'] = 'nullable|email'; 
    }

    // 3. Execute validation
    $validated = $request->validate($rules);

        if ($doctor->user){
            $doctor->user->update([
                'name'  => $validated['name'],
                'email' => $validated['email']
            ]);
        }

        $doctor->update([
           'name'         => $validated['name'],
           'email'        => $validated['email'],
           'specialty_id' => $validated['specialty_id'],
           'speciality'   => Specialty::find($validated['specialty_id'])->name,
           'phone_number' => $validated['phone_number'],
        ]);

        $doctor->schedules()->delete();
        if ($request->has('schedules')) {
          foreach ($request->schedules as $schedule) {
            if(!empty($schedule['day_of_week']) && !empty($schedule['start_time']) && !empty($schedule['end_time'])) {
                $doctor->schedules()->create($schedule);
            }
            }
        }

        return redirect()->route('doctors.index')->with('success', 'Doctor details synchronized.');
    }

    public function destroy(Doctor $doctor)
{
    if ($doctor->user_id) {
        User::destroy($doctor->user_id);
    }
    
    $doctor->delete();
    
    return redirect()->route('doctors.index')->with('success', 'Doctor removed from active logs.');
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

        return redirect()->route('doctor.dashboard', $patientId)->with('success', 'Medical file updated successfully.');
    }

    public function showPatientRecord($patientId){
        
        $patient = \App\Models\Patient::findOrFail($patientId);
       

        return view('doctor.patient-record', compact('patient'));
    }
    
    public function myDepartmentPatients(Request $request)
{
    $doctorUser = Auth::user();
    
    if (!$doctorUser->doctorProfile) {
        abort(404, 'Doctor profile missing.');
    }

    $specialtyId = $doctorUser->doctorProfile->specialty_id;
    $searchTerm = $request->input('search'); 

  
    $query = Appointment::whereHas('doctor', function($q) use ($specialtyId) {
        $q->where('specialty_id', $specialtyId);
    })->with('patient');

   
    if ($searchTerm) {
        $query->whereHas('patient', function($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%');
        });
    }

    $appointments = $query->latest()->get();

    return view('doctor.dashboard', compact('appointments'));
}
}