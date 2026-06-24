@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
  

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-[#1A3263]">Edit Doctor Record</h2>
            <p class="text-sm text-slate-500">Update details for <span class="font-semibold text-slate-700">{{ $doctor->name }}</span></p>
        </div>

        <form action="{{ route('doctors.update', $doctor->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                    <input type="text" name="name" id="name" 
                        value="{{ old('name', $doctor->name) }}" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    @error('name') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="phone_number" class="block text-sm font-semibold text-slate-700">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" 
                        value="{{ old('phone_number', $doctor->phone_number) }}" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    @error('phone_number') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
                    <input type="email" name="email" id="email" 
                        value="{{ old('email', $doctor->email) }}" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    @error('email') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="specialty_id" class="block text-sm font-semibold text-slate-700">Medical Specialty</label>
                    <select name="specialty_id" id="specialty_id" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">-- Select Specialty --</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ $doctor->specialty_id == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialty_id') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('doctors.index') }}" 
                   class="px-6 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 rounded-lg bg-[#1A3263] text-white hover:bg-blue-800 transition font-medium shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection