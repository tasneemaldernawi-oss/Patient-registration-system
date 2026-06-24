@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded-xl shadow border border-slate-200 mb-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-4">{{ $patient->name }}</h1>
        <div class="grid grid-cols-2 gap-4 text-sm text-slate-600">
            <div>
                <span class="block font-bold text-xs uppercase text-slate-400">Gender</span>
                {{ $patient->gender ?? 'Not Specified' }}
            </div>
            <div>
                <span class="block font-bold text-xs uppercase text-slate-400">Phone</span>
                {{ $patient->phone_number }}
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border border-slate-200">
        <h2 class="text-lg font-semibold text-slate-700 mb-4">Add Medical Document</h2>
        
        <form action="{{ route('doctor.add-diagnosis', $patient->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700">Diagnosis/Notes</label>
                <textarea name="diagnosis" class="w-full mt-1 rounded-lg border-slate-300" rows="3" required></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700">Attachment (PDF/JPG/PNG)</label>
                <input type="file" name="medical_file" class="mt-1 block w-full text-sm text-slate-500 
                    file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 
                    file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <button type="submit" class="bg-[#1A3263] text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-900">
                Submit Record
            </button>
        </form>
    </div>
</div>
@endsection