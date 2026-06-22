@extends('layouts.app') @section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Department Clinical Dashboard</h1>
        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
            Logged in as: {{ Auth::user()->name }}
        </span>
    </div>

    <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50">
            <h2 class="font-semibold text-slate-700">Today's Assigned Department Patients</h2>
        </div>
        
        <div class="p-6">
            @if($appointments->isEmpty())
                <p class="text-slate-500 text-center py-4">No patients scheduled in your department today.</p>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-sm">
                            <th class="pb-3">Patient Name</th>
                            <th class="pb-3">Scheduled Time</th>
                            <th class="pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($appointments as $appointment)
                            <tr>
                                <td class="py-3 font-medium text-slate-800">{{ $appointment->patient->name }}</td>
                                <td class="py-3 text-slate-600">{{ $appointment->time }}</td>
                                <td class="py-3 text-right">
                                    <button class="bg-[#1A3263] text-white text-xs px-3 py-1.5 rounded font-semibold hover:bg-blue-900 transition-colors">
                                        Add Medical Record
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection