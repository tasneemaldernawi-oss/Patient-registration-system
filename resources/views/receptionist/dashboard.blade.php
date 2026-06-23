@extends('layouts.app') 

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Receptionist Front-Desk Dashboard</h1>
        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
            Logged in as: {{ Auth::user()->name }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow border border-slate-200 p-5 flex items-center">
            <div class="p-3 bg-blue-50 text-[#1A3263] rounded-lg mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium uppercase tracking-wider">Total Registered Patients</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalPatients }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border border-slate-200 p-5 flex items-center">
            <div class="p-3 bg-green-50 text-green-600 rounded-lg mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium uppercase tracking-wider">Today's Appointments</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $todayAppointmentsCount }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border border-slate-200 p-5 flex items-center">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-lg mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium uppercase tracking-wider">Pending Confirmation</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $pendingCount }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50">
            <h2 class="font-semibold text-slate-700">Today's Schedule Divided by Speciality</h2>
        </div>
        
        <div class="p-6 space-y-8">
            @if($appointmentsBySpeciality->isEmpty())
                <p class="text-slate-500 text-center py-4">No appointments scheduled for today yet.</p>
            @else
                @foreach($appointmentsBySpeciality as $specialityName => $group)
                    <div class="border border-slate-100 rounded-lg p-4 bg-slate-50/50">
                        <h3 class="text-lg font-bold text-[#1A3263] mb-3 flex items-center">
                            <span class="inline-block w-2 h-4 bg-[#1A3263] rounded-sm mr-2"></span>
                            {{ $specialityName }} 
                            <span class="ml-2 bg-slate-200 text-slate-700 text-xs px-2 py-0.5 rounded-full font-normal">
                                {{ $group->count() }} {{ Str::plural('appointment', $group->count()) }}
                            </span>
                        </h3>

                        <table class="w-full text-left border-collapse bg-white rounded-md overflow-hidden shadow-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-100 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                                    <th class="p-3">Patient Name</th>
                                    <th class="p-3">Assigned Doctor</th>
                                    <th class="p-3">Time</th>
                                    <th class="p-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($group as $appointment)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="p-3 font-medium text-slate-800">{{ $appointment->patient->name }}</td>
                                        <td class="p-3 text-slate-600">
                                            Dr. {{ $appointment->doctor->user->name ?? 'Assigned Doctor' }}
                                        </td>
                                        <td class="p-3 text-slate-600 font-mono">{{ $appointment->time }}</td>
                                        <td class="p-3">
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                                                {{ $appointment->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection