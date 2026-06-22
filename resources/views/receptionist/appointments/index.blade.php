@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen" 
     x-data="appointmentFlow()" 
     x-init="init()">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Appointments</h1>
            <p class="text-sm text-slate-500">Manage and monitor all scheduled medical visits.</p>
        </div>
        <button @click="toggleBooking()"
           class="bg-[#1A3263] hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-sm"
           x-show="!showBooking">
            + Book Appointment
        </button>
    </div>
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Multi-Step Booking Form Section -->
    <div x-show="showBooking" x-transition class="mb-8">
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Book New Appointment</h2>
                        <p class="text-sm text-slate-600 mt-1">Follow the steps to schedule an appointment</p>
                    </div>
                    <button @click="toggleBooking()" class="text-slate-400 hover:text-slate-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Step Indicator -->
                <div class="mt-6 flex items-center justify-between">
                    <template x-for="(step, index) in steps" :key="index">
                        <div class="flex items-center flex-1">
                            <div :class="[
                                'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition',
                                currentStep > index ? 'bg-emerald-500 text-white' : 
                                currentStep === index ? 'bg-blue-500 text-white ring-4 ring-blue-200' : 
                                'bg-slate-200 text-slate-500'
                            ]">
                                <span x-show="currentStep > index">✓</span>
                                <span x-show="currentStep <= index" x-text="index + 1"></span>
                            </div>
                            <p :class="[
                                'text-xs font-medium ml-3 flex-1',
                                currentStep >= index ? 'text-slate-900' : 'text-slate-400'
                            ]" x-text="step"></p>
                            <div x-show="index < steps.length - 1" class="flex-1 h-1 mx-2" :class="currentStep > index ? 'bg-emerald-500' : 'bg-slate-200'"></div>
                        </div>
                    </template>
                </div>
            </div>

            <form @submit.prevent="submitForm()" class="p-8 space-y-6">
                @csrf

                <!-- Step 1: Select Specialty -->
                <div x-show="currentStep === 0" x-transition>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Select Medical Specialty</h3>
                            <p class="text-sm text-slate-500">Choose the clinical department required for the new appointment.</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            <template x-for="specialty in specialties" :key="specialty.id">
                                <button type="button"
                                        @click="selectSpecialty(specialty.id)"
                                        :class="formData.specialty_id === specialty.id ? 'border-blue-500 bg-blue-50 shadow-sm' : 'border-slate-200 bg-white'"
                                        class="w-full p-6 text-left rounded-3xl border transition hover:border-blue-400 hover:bg-slate-50">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-blue-50 text-blue-600 text-2xl">
                                        <span x-text="getIcon(specialty.icon)"></span>
                                    </div>
                                    <div class="mt-5">
                                        <p class="text-base font-semibold text-slate-900" x-text="specialty.name"></p>
                                        <p class="mt-3 text-sm leading-6 text-slate-500" x-text="specialty.description"></p>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Select Doctor -->
                <div x-show="currentStep === 1" x-transition>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Select Doctor</h3>
                            <p class="text-sm text-slate-500">Choose a provider in the selected specialty.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <template x-for="doctor in availableDoctors()" :key="doctor.id">
                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:bg-slate-50"
                                       :class="formData.doctor_id == doctor.id ? 'border-blue-500 bg-blue-50' : 'border-slate-200'">
                                    <input type="radio" name="doctor_id" :value="doctor.id" x-model="formData.doctor_id" class="w-4 h-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm ml-2 flex-shrink-0"
                                         x-text="doctor.name.charAt(0).toUpperCase()"></div>
                                    <div class="ml-4">
                                        <p class="font-medium text-slate-900" x-text="'Dr. ' + doctor.name"></p>
                                        <p class="text-sm text-slate-500" x-text="doctor.speciality"></p>
                                    </div>
                                </label>
                            </template>
                            <template x-if="availableDoctors().length === 0">
                                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                                    No doctors available for the selected specialty yet.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div x-show="currentStep === 2" x-transition>
    <input type="hidden" name="patient_mode" :value="patientMode">
    <input type="hidden" name="patient_id" :value="formData.patient_id">

    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-slate-900">Patient Information Records</h3>
                <p class="text-sm text-slate-500">Associate an existing clinical record or declare a new registration patient path.</p>
            </div>
            <div class="flex items-center gap-2 rounded-full bg-slate-100 p-1">
                <button type="button" @click="patientMode = 'existing'" :class="patientMode === 'existing' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9.003 9.003 0 0112 15c2.037 0 3.918.648 5.379 1.748M15 11a3 3 0 11-6 0 3 3 0 016 0Zm6 2.25a9 9 0 11-18 0 9 9 0 0118 0Z" />
                    </svg>
                    Existing Patient
                </button>
                <button type="button" @click="patientMode = 'new'" :class="patientMode === 'new' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25a3 3 0 11-6 0 3 3 0 016 0Zm-6.75 4.5a5.25 5.25 0 1110.5 0v1.5m-9.75 4.5h8.25M12 7.5v3.75" />
                    </svg>
                    Create New Record
                </button>
            </div>
        </div>
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <div x-show="patientMode === 'existing'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="mb-5">
                    <label class="relative block">
                        <span class="sr-only">Search patients</span>
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15Z" />
                            </svg>
                        </span>
                        <input type="text" x-model="patientSearch" placeholder="Filter existing patients list by full name or telephone..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition" />
                    </label>
                </div>
                <div class="max-h-[420px] overflow-y-auto space-y-3">
                    <template x-if="filteredPatients().length === 0">
                        <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">
                            No matching patients found.
                        </div>
                    </template>
                    <template x-for="patient in filteredPatients()" :key="patient.id">
                        <button type="button" @click="formData.patient_id = patient.id" class="w-full rounded-3xl border p-4 text-left transition hover:border-blue-300 hover:bg-slate-50" :class="formData.patient_id == patient.id ? 'border-blue-500 bg-blue-50 shadow-sm' : 'border-slate-200 bg-white'">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                                    <span x-text="getInitials(patient.name)"></span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900" x-text="patient.name"></p>
                                    <p class="mt-1 text-sm text-slate-500 truncate">DOB: <span x-text="patient.date_of_birth || patient.dob"></span> | Tel: <span x-text="patient.phone"></span></p>
                                </div>
                                <div class="flex h-9 w-9 items-center justify-center rounded-full border text-slate-500" :class="formData.patient_id == patient.id ? 'border-blue-500 text-blue-600' : 'border-slate-200'">
                                    <svg x-show="formData.patient_id == patient.id" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span x-show="formData.patient_id != patient.id">+</span>
                                </div>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
            <div x-show="patientMode === 'new'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="space-y-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Full Name</label>
                        <input type="text" name="name" x-model="newPatient.name" placeholder="Jane Doe" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition" />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" x-model="newPatient.email" placeholder="jane@example.com" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition" />
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Phone Number</label>
                        <input type="tel" name="phone" x-model="newPatient.phone" placeholder="+1 (555) 349-8091" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition" />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Date of Birth</label>
                        <input type="date" name="date_of_birth" x-model="newPatient.date_of_birth" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition" />
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Gender</label>
                        <select name="gender" x-model="newPatient.gender" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition">
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                    <p class="font-medium text-slate-900">New patient registration preview</p>
                    <p class="mt-2">Complete the essential contact fields to create a new patient record for the appointment.</p>
                </div>
            </div>
        </div>
    </div>
</div>
                <!-- Step 4: Appointment Details -->
                <div x-show="currentStep === 3" x-transition>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Appointment Details</h3>
                            <p class="text-sm text-slate-500">Set the appointment time and provide any context for the visit.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-700">Appointment Date</label>
                                <input type="date" name="date" x-model="formData.date" :min="minDate" 
                                       class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                       required>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-700">Appointment Time</label>
                                <input type="time" name="time" x-model="formData.time" 
                                       class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                       required>
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700">Reason for Visit</label>
                                <textarea name="reason" x-model="formData.reason" rows="4" 
                                          placeholder="Describe the reason for this appointment..."
                                          class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"></textarea>
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700">Status</label>
                                <select name="status" x-model="formData.status" 
                                        class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Confirmation -->
                <div x-show="currentStep === 4" x-transition>
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-slate-900">Confirm Appointment Details</h3>
                        <div class="bg-slate-50 rounded-lg p-6 space-y-4">
                            <div class="flex justify-between items-start pb-4 border-b border-slate-200">
                                <div>
                                    <p class="text-sm text-slate-600">Specialty</p>
                                    <p class="font-medium text-slate-900" x-text="getSpecialtyName()"></p>
                                </div>
                            </div>
                            <div class="flex justify-between items-start pb-4 border-b border-slate-200">
                                <div>
                                    <p class="text-sm text-slate-600">Patient</p>
                                    <p class="font-medium text-slate-900" x-text="getPatientName()"></p>
                                </div>
                            </div>
                            <div class="flex justify-between items-start pb-4 border-b border-slate-200">
                                <div>
                                    <p class="text-sm text-slate-600">Doctor</p>
                                    <p class="font-medium text-slate-900" x-text="getDoctorName()"></p>
                                </div>
                            </div>
                            <div class="flex justify-between items-start pb-4 border-b border-slate-200">
                                <div>
                                    <p class="text-sm text-slate-600">Date & Time</p>
                                    <p class="font-medium text-slate-900" x-text="getDateTime()"></p>
                                </div>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-slate-600">Reason for Visit</p>
                                    <p class="font-medium text-slate-900" x-text="formData.reason || 'Not specified'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between pt-8 border-t border-slate-200">
                    <button type="button" @click="previousStep()" x-show="currentStep > 0"
                            class="px-6 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">
                        ← Back
                    </button>
                    <div class="flex gap-3" x-show="currentStep < 4">
                        <button type="button" @click="nextStep()" :disabled="!canProceed()"
                                :class="canProceed() ? 'bg-[#1A3263] hover:bg-blue-700 text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                class="px-6 py-2 rounded-lg transition font-medium shadow-sm">
                            Next Step →
                        </button>
                    </div>
                    <div class="flex gap-3" x-show="currentStep === 4">
                        <button type="button" @click="previousStep()"
                                class="px-6 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">
                            ← Back
                        </button>
                        <button type="submit"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg transition font-medium shadow-sm">
                            Confirm & Book
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Appointments List Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Patient</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Assigned Doctor</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Schedule</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Reason</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-slate-900">{{ $appointment->patient->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $appointment->patient->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold">
                                            {{ substr($appointment->doctor->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">Dr. {{ $appointment->doctor->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $appointment->doctor->speciality }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($appointment->date)->format('M d, Y') }}</span>
                                        <span>{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    <div class="max-w-xs truncate">{{ $appointment->reason }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'pending'   => 'bg-[#FFFAE5] text-amber-700 border-[#FFFAE5]',
                                            'confirmed' => 'bg-[#F0FEED] text-emerald-700 border-[#F0FEED]',
                                            'cancelled' => 'bg-rose-100 text-rose-700 border-rose-100',
                                        ];
                                        $class = $statusClasses[$appointment->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $class }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3 whitespace-nowrap">
                                        <a href="{{ route('appointments.edit', $appointment->id) }}" class="text-blue-700 hover:text-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Cancel this appointment?')" class="text-rose-500 hover:text-rose-700 text-sm font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-12 h-12 mb-4 text-slate-300">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                        </svg>
                                        <p class="font-medium">No appointments found.</p>
                                        <p class="text-sm">New appointments will appear here once booked.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function appointmentFlow() {
        return {
            showBooking: false,
            currentStep: 0,
            steps: [
                'Specialty',
                'Doctor',
                'Patient',
                'Appointment',
                'Confirmation'
            ],
            specialties: @json($specialties),
            patients: @json($patients),
            doctors: @json($doctors),
            patientMode: 'existing',
            patientSearch: '',
            minDate: new Date().toISOString().split('T')[0],
            formData: {
                specialty_id: '',
                patient_id: '',
                doctor_id: '',
                date: '',
                time: '',
                reason: '',
                status: 'pending'
            },
            newPatient: {
                name: '',
                email: '',
                phone: '',
                date_of_birth: '',
                gender: ''
            },

            init() {
                // Check if coming from a redirect
                if (window.location.hash === '#booking') {
                    this.showBooking = true;
                }
            },

            toggleBooking() {
                this.showBooking = !this.showBooking;
                if (!this.showBooking) {
                    this.resetForm();
                }
            },

            canProceed() {
                switch(this.currentStep) {
                    case 0:
                        return this.formData.specialty_id !== '';
                    case 1:
                        return this.formData.doctor_id !== '';
                    case 2:
                        return this.patientMode === 'existing' ? this.formData.patient_id !== '' : this.isNewPatientValid();
                    case 3:
                        return this.formData.date !== '' && this.formData.time !== '';
                    case 4:
                        return true;
                    default:
                        return false;
                }
            },

            filteredPatients() {
                if (!this.patientSearch) return this.patients;
                return this.patients.filter(patient => {
                    const query = this.patientSearch.toLowerCase();
                    return patient.name.toLowerCase().includes(query) || patient.phone.toLowerCase().includes(query);
                });
            },

            isNewPatientValid() {
                return this.newPatient.name.trim() !== '' && this.newPatient.email.trim() !== '' && this.newPatient.phone.trim() !== '' && this.newPatient.date_of_birth !== '' && (this.newPatient.gender === 'Male' || this.newPatient.gender === 'Female');
            },

            getInitials(name) {
                return name.split(' ').slice(0,2).map(part => part.charAt(0).toUpperCase()).join('');
            },

            availableDoctors() {
                return this.doctors.filter(d => d.specialty_id == this.formData.specialty_id);
            },

            selectSpecialty(id) {
                this.formData.specialty_id = id;
                this.formData.doctor_id = '';
            },

            getIcon(name) {
                const icons = {
                    tooth: '🦷',
                    sparkles: '✨',
                    heart: '❤️',
                    child: '👶',
                    brain: '🧠',
                };
                return icons[name] || '⚕️';
            },

            nextStep() {
                if (this.canProceed() && this.currentStep < this.steps.length - 1) {
                    this.currentStep++;
                }
            },

            previousStep() {
                if (this.currentStep > 0) {
                    this.currentStep--;
                }
            },

            getPatientName() {
                if (this.patientMode === 'new' && this.newPatient.name.trim() !== '') {
                    return this.newPatient.name;
                }
                const patient = this.patients.find(p => p.id == this.formData.patient_id);
                return patient ? patient.name : '';
            },

            getDoctorName() {
                const doctor = this.doctors.find(d => d.id == this.formData.doctor_id);
                return doctor ? 'Dr. ' + doctor.name : '';
            },

            getSpecialtyName() {
                const specialty = this.specialties.find(s => s.id == this.formData.specialty_id);
                return specialty ? specialty.name : '';
            },

            getDateTime() {
                if (!this.formData.date || !this.formData.time) return '';
                const dateObj = new Date(this.formData.date + 'T' + this.formData.time);
                return dateObj.toLocaleDateString('en-US', { 
                    weekday: 'short', 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                }) + ' at ' + this.formData.time;
            },

            resetForm() {
                this.currentStep = 0;
                this.patientMode = 'existing';
                this.patientSearch = '';
                this.formData = {
                    specialty_id: '',
                    patient_id: '',
                    doctor_id: '',
                    date: '',
                    time: '',
                    reason: '',
                    status: 'pending'
                };
                this.newPatient = {
                    name: '',
                    email: '',
                    phone: '',
                    dob: ''
                };
            },

            async submitForm() {
    // If creating a new patient, create it first via AJAX
    try {
        if (this.patientMode === 'new') {
            const pForm = new FormData();
            pForm.append('_token', document.querySelector('input[name="_token"]').value);
            pForm.append('name', this.newPatient.name);
            pForm.append('email', this.newPatient.email);
            pForm.append('phone', this.newPatient.phone);
            pForm.append('date_of_birth', this.newPatient.date_of_birth);
            pForm.append('gender', this.newPatient.gender);
            pForm.append('address', 'Not provided'); // Placeholder, adjust as needed

            const pResp = await fetch('{{ route("patients.store") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: pForm
            });

            if (!pResp.ok) {
                const errorData = await pResp.json();
                console.error('Patient create failed:', errorData);
                
                // Display validation errors if available
                if (errorData.errors) {
                    const errorMessages = Object.values(errorData.errors).flat().join('\n');
                    alert('Validation errors:\n' + errorMessages);
                } else if (errorData.message) {
                    alert('Error: ' + errorData.message);
                } else {
                    alert('Error creating patient record');
                }
                return;
            }

            const created = await pResp.json();
            
            // Check if the response has the expected structure
            if (created.id) {
                this.formData.patient_id = created.id;
            } else if (created.data && created.data.id) {
                // Handle wrapped responses
                this.formData.patient_id = created.data.id;
            } else {
                console.error('Unexpected response format:', created);
                alert('Unexpected response from server');
                return;
            }
        }

        // Submit appointment
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('patient_id', this.formData.patient_id);
        formData.append('doctor_id', this.formData.doctor_id);
        formData.append('date', this.formData.date);
        formData.append('time', this.formData.time);
        formData.append('reason', this.formData.reason);
        formData.append('status', this.formData.status);

        const response = await fetch('{{ route("appointments.store") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        });

        if (response.ok) {
            // Reload to show success message
            window.location.reload();
        } else {
            const errorData = await response.json();
            console.error('Appointment creation failed:', errorData);
            
            if (errorData.errors) {
                const errorMessages = Object.values(errorData.errors).flat().join('\n');
                alert('Validation errors:\n' + errorMessages);
            } else if (errorData.message) {
                alert('Error: ' + errorData.message);
            } else {
                alert('Error booking appointment');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error submitting form: ' + error.message);
    }
}
        }
    }
</script>

@endsection