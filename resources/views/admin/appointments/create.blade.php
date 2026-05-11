@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-800">Appointment Information</h2>
            <p class="text-sm text-slate-500">Fill in the details to make a new appointment.</p>
        </div>
        <form action="{{ route('appointments.store') }}" method="POST" class="p-8 space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700">Select Patient</label>
            <select name="patient_id" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
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
            <input type="date" name="date" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
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

@endsection