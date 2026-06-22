@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-800">Appointment Information</h2>
            <p class="text-sm text-slate-500">Fill in the details to make a new appointment.</p>
        </div>
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700" >
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('appointments.store') }}" method="POST" class="p-8 space-y-6">
    @csrf

    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 space-y-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Register New Patient</h3>
                <p class="text-sm text-slate-500">Add a patient record and select it for this appointment.</p>
            </div>
            <button type="button" id="registerPatientBtn" class="inline-flex items-center justify-center rounded-lg bg-[#1A3263] px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                Register Patient
            </button>
        </div>

        <div id="registerPatientMessages"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Full Name</label>
                <input type="text" id="newPatientName" placeholder="Jane Doe" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" />
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Email</label>
                <input type="email" id="newPatientEmail" placeholder="jane@example.com" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" />
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Phone</label>
                <input type="tel" id="newPatientPhone" placeholder="+1 555 123 4567" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" />
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Date of Birth</label>
                <input type="date" id="newPatientDob" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" />
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Gender</label>
                <select id="newPatientGender" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="">Select gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700">Address</label>
                <textarea id="newPatientAddress" rows="2" placeholder="Patient address" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700">Select Patient</label>
            <select name="patient_id" id="patientSelect" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">-- Choose Patient --</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700">Assign Doctor</label>
            <select name="doctor_id" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">-- Choose Doctor --</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} ({{ $doctor->speciality }})</option>
                @endforeach
            </select>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700">Appointment Date</label>
            <input type="date" name="date" min="{{ date('Y-m-d') }}" value="{{ old('date') }}" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">

            @error('date')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror

        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700">Time</label>
            <input type="time" name="time" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700">Status</label>
            <select name="status" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                <option value="pending" selected>Pending</option>
                <option value="confirmed" >Confirmed</option>
                <option value="cancelled" >Cancelled</option>

            </select>
        </div>
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-semibold text-slate-700">Reason for Visit</label>
        <textarea name="reason" rows="3" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
    </div>

    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
        <a href="{{ route('appointments.index') }}" 
            class="px-6 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">
             Cancel
        </a>
        <button type="submit" class="bg-[#1A3263] text-white px-6 py-2 rounded-lg hover:bg-blue-200 transition shadow-sm">
            Confirm Appointment
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('registerPatientBtn');
        const messages = document.getElementById('registerPatientMessages');
        const patientSelect = document.getElementById('patientSelect');

        btn.addEventListener('click', async function () {
            messages.innerHTML = '';

            const payload = {
                name: document.getElementById('newPatientName').value.trim(),
                email: document.getElementById('newPatientEmail').value.trim(),
                phone: document.getElementById('newPatientPhone').value.trim(),
                date_of_birth: document.getElementById('newPatientDob').value,
                gender: document.getElementById('newPatientGender').value,
                address: document.getElementById('newPatientAddress').value.trim(),
            };

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value;

            try {
                const response = await fetch("{{ route('patients.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json();

                if (!response.ok) {
                    let errorHtml = '<div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700"><ul class="mt-2 list-disc list-inside">';
                    const errors = data.errors || data.message ? data.errors || [data.message] : ['An error occurred while registering the patient.'];
                    for (const key in errors) {
                        if (Array.isArray(errors[key])) {
                            errors[key].forEach(err => {
                                errorHtml += `<li>${err}</li>`;
                            });
                        } else {
                            errorHtml += `<li>${errors[key]}</li>`;
                        }
                    }
                    errorHtml += '</ul></div>';
                    messages.innerHTML = errorHtml;
                    return;
                }

                patientSelect.insertAdjacentHTML('beforeend', `<option value="${data.id}" selected>${data.name}</option>`);
                patientSelect.value = data.id;

                messages.innerHTML = '<div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-400 text-emerald-700">Patient registered successfully.</div>';
                btn.textContent = 'Patient Registered';
                btn.disabled = true;
            } catch (error) {
                messages.innerHTML = '<div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700">Unable to register patient. Please try again.</div>';
            }
        });
    });
</script>

@endsection