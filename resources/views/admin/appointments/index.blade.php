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

                <!-- Step 1: Select Patient -->
                <div x-show="currentStep === 0" x-transition>
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-slate-900">Select Patient</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <template x-for="patient in patients" :key="patient.id">
                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:bg-slate-50" 
                                       :class="formData.patient_id == patient.id ? 'border-blue-500 bg-blue-50' : 'border-slate-200'">
                                    <input type="radio" name="patient_id" :value="patient.id" x-model="formData.patient_id" class="w-4 h-4">
                                    <div class="ml-4">
                                        <p class="font-medium text-slate-900" x-text="patient.name"></p>
                                        <p class="text-sm text-slate-500" x-text="'Phone: ' + patient.phone"></p>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Select Doctor -->
                <div x-show="currentStep === 1" x-transition>
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-slate-900">Select Doctor</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <template x-for="doctor in doctors" :key="doctor.id">
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
                        </div>
                    </div>
                </div>

                <!-- Step 3: Select Date & Time -->
                <div x-show="currentStep === 2" x-transition>
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-slate-900">Choose Date & Time</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        </div>
                    </div>
                </div>

                <!-- Step 4: Add Reason & Status -->
                <div x-show="currentStep === 3" x-transition>
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-slate-900">Additional Details</h3>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-700">Reason for Visit</label>
                                <textarea name="reason" x-model="formData.reason" rows="4" 
                                          placeholder="Describe the reason for this appointment..."
                                          class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"></textarea>
                            </div>
                            <div class="space-y-2">
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
                        <button type="button" @click="toggleBooking()"
                                class="px-6 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">
                            Cancel
                        </button>
                        <button type="button" @click="nextStep()" :disabled="!canProceed()"
                                :class="canProceed() ? 'bg-[#1A3263] hover:bg-blue-700 text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                class="px-6 py-2 rounded-lg transition font-medium shadow-sm">
                            Next →
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
                'Select Patient',
                'Select Doctor',
                'Date & Time',
                'Additional Details',
                'Confirmation'
            ],
            patients: @json($patients),
            doctors: @json($doctors),
            minDate: new Date().toISOString().split('T')[0],
            formData: {
                patient_id: '',
                doctor_id: '',
                date: '',
                time: '',
                reason: '',
                status: 'pending'
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
                        return this.formData.patient_id !== '';
                    case 1:
                        return this.formData.doctor_id !== '';
                    case 2:
                        return this.formData.date !== '' && this.formData.time !== '';
                    case 3:
                        return true; // Reason is optional, status has default
                    default:
                        return false;
                }
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
                const patient = this.patients.find(p => p.id == this.formData.patient_id);
                return patient ? patient.name : '';
            },

            getDoctorName() {
                const doctor = this.doctors.find(d => d.id == this.formData.doctor_id);
                return doctor ? 'Dr. ' + doctor.name : '';
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
                this.formData = {
                    patient_id: '',
                    doctor_id: '',
                    date: '',
                    time: '',
                    reason: '',
                    status: 'pending'
                };
            },

            async submitForm() {
                // Submit the form
                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('patient_id', this.formData.patient_id);
                formData.append('doctor_id', this.formData.doctor_id);
                formData.append('date', this.formData.date);
                formData.append('time', this.formData.time);
                formData.append('reason', this.formData.reason);
                formData.append('status', this.formData.status);

                try {
                    const response = await fetch('{{ route("appointments.store") }}', {
                        method: 'POST',
                        body: formData
                    });

                    if (response.ok) {
                        // Reload to show success message
                        window.location.reload();
                    } else {
                        alert('Error booking appointment');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error submitting form');
                }
            }
        }
    }
</script>

@endsection