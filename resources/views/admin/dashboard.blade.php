@extends('layouts.app')

@section('content')
<div class="p-4 bg-slate-50 min-h-screen" 
     x-data="{ loading: true }" 
     x-init="setTimeout(() => loading = false, 700)">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Medical System</h1>
            <p class="text-sm text-slate-500">Admin Portal</p>
        </div>
        
    </div>

    <div x-show="loading" class="animate-pulse">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            @for($i = 0; $i < 4; $i++)
                <div class="bg-white p-6 rounded-xl border border-slate-100 h-32">
                    <div class="h-4 bg-slate-200 rounded w-1/2 mb-4"></div>
                    <div class="h-8 bg-slate-100 rounded w-1/4"></div>
                </div>
            @endfor
        </div>
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-9 bg-white rounded-xl border border-slate-100 h-96 p-6">
                <div class="h-6 bg-slate-200 rounded w-1/4 mb-6"></div>
                <div class="space-y-4">
                    @for($i = 0; $i < 6; $i++)
                        <div class="h-12 bg-slate-50 rounded w-full"></div>
                    @endfor
                </div>
            </div>
            <div class="col-span-12 lg:col-span-3 bg-white rounded-xl border border-slate-100 h-80 p-6">
                <div class="h-6 bg-slate-200 rounded w-1/2 mb-6"></div>
                <div class="space-y-6">
                    @for($i = 0; $i < 4; $i++)
                        <div class="h-10 bg-slate-50 rounded w-full"></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <div x-show="!loading" x-cloak x-transition.opacity.duration.400ms>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Daily Load</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $dailyLoad }}</h3>
                    <p class="text-xs text-slate-400 mt-1">Today's Appointments</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Specialist Count</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $speciallistCount }}</h3>
                    <p class="text-xs text-slate-400 mt-1">Total Specialists</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                    </svg>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Growth</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $totalPatients }}</h3>
                    <p class="text-xs text-slate-400 mt-1">Total Patients</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Attention Required</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $pendingAppointments }}</h3>
                    <p class="text-xs text-slate-400 mt-1">Pending Appointments</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-9 bg-white rounded-xl shadow-sm border border-slate-100">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                    <h2 class="font-bold text-slate-800 flex items-center gap-2">
                        <span class="text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                        </span> Daily Schedule
                    </h2>
                    <span class="text-sm text-slate-400">{{ now()->format('F d, Y') }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-400 text-sm uppercase tracking-wider">
                                <th class="px-6 py-4 font-medium">Time</th>
                                <th class="px-6 py-4 font-medium">Patient Info</th>
                                <th class="px-6 py-4 font-medium">Doctor Assignment</th>
                                <th class="px-6 py-4 font-medium">Visit Context</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($appointments as $appointment)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-bold text-slate-700">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $appointment->patient->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $appointment->patient->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-700">Dr. {{ $appointment->doctor->name }}</div>
                                    <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded uppercase font-bold">
                                        {{ $appointment->doctor->speciality }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-sm italic">"{{ $appointment->reason }}"</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'confirmed' => 'bg-emerald-100 text-emerald-700',
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'cancelled' => 'bg-rose-100 text-rose-700'
                                        ][$appointment->status] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span class="{{ $statusClasses }} px-3 py-1 rounded-full text-xs font-bold capitalize">
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-blue-500">
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-3 space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                    <h3 class="font-bold text-slate-800 mb-4">Recent Patients</h3>
                    <div class="space-y-4">
                        @foreach($recentPatients as $recent)
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-slate-800 text-sm">{{ $recent->name }}</p>
                                <p class="text-xs text-slate-400">{{ $recent->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection