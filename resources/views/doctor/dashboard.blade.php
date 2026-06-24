@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center shadow-sm">
             {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Clinical Dashboard</h1>
            <p class="text-slate-500 text-sm">Manage patient records for your department</p>
        </div>
        <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-200">
            Dr. {{ Auth::user()->name }}
        </span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
    <div class="p-5 border-b border-slate-100 bg-slate-50">
    <h2 class="font-semibold text-slate-700 mb-4">Assigned Department Patients</h2>
    
    <form action="{{ route('doctor.dashboard') }}" method="GET" class="flex gap-2 w-full">
        <input type="text" name="search" value="{{ request('search') }}" 
            placeholder="Search patients by name..." 
            class="w-full px-4 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        
        <button type="submit" class="bg-[#1A3263] text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-blue-900 transition whitespace-nowrap">
            Search
        </button>
        
        @if(request('search'))
            <a href="{{ route('doctor.dashboard') }}" class="px-4 py-2 text-slate-500 hover:text-slate-800 transition text-sm flex items-center">
                Clear
            </a>
        @endif
    </form>
</div>
        
        <div class="overflow-x-auto">
            @if($appointments->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <p>No patients found matching your search.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50">
                        <tr class="text-slate-400 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4">Patient Name</th>
                            <th class="px-6 py-4">Scheduled Time</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($appointments as $appointment)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $appointment->patient->name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $appointment->time }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('doctor.patient.record', $appointment->patient->id) }}" 
                                       class="inline-block bg-[#1A3263] text-white text-xs px-4 py-2 rounded-lg font-semibold hover:bg-blue-900 transition">
                                        View File
                                    </a>
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