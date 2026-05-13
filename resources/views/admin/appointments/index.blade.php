@extends('layouts.app')

@section('content')
<div class="p-4 bg-slate-50 min-h-screen" 
     x-data="{ loading: true }" 
     x-init="setTimeout(() => loading = false, 600)">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Appointments</h1>
            <p class="text-sm text-slate-500">Manage and monitor all scheduled medical visits.</p>
        </div>
        <a href="{{ route('appointments.create') }}" 
           class="bg-[#1A3263] hover:bg-blue-200 text-white px-4 py-2 rounded-lg transition shadow-sm">
            + Book Appointment
        </a>
    </div>
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div x-show="loading" class="animate-pulse">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="h-12 bg-slate-50 border-b border-slate-100"></div>
            <div class="p-0">
                @for($i = 0; $i < 5; $i++)
                <div class="flex items-center px-6 py-4 border-b border-slate-50 gap-6">
                    <div class="w-1/4">
                        <div class="h-4 bg-slate-200 rounded w-3/4 mb-2"></div>
                        <div class="h-3 bg-slate-100 rounded w-1/2"></div>
                    </div>
                    <div class="w-1/4 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-slate-100"></div>
                        <div class="flex-1">
                            <div class="h-3 bg-slate-200 rounded w-full mb-1"></div>
                            <div class="h-2 bg-slate-100 rounded w-1/2"></div>
                        </div>
                    </div>
                    <div class="w-1/6">
                        <div class="h-3 bg-slate-100 rounded w-full mb-1"></div>
                        <div class="h-3 bg-slate-100 rounded w-2/3"></div>
                    </div>
                    <div class="w-1/6">
                        <div class="h-4 bg-slate-50 rounded w-full"></div>
                    </div>
                    <div class="w-1/12">
                        <div class="h-6 bg-slate-100 rounded-full w-full"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>

    <div x-show="!loading" x-cloak x-transition.opacity.duration.400ms>
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
@endsection