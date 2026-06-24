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
                <div class="mt-8">
             
    <h3 class="text-lg font-bold text-slate-800 mb-4">Availability Schedule</h3>
    
    <div id="schedule-container" class="space-y-4">
        @forelse($doctor->schedules as $index => $schedule)
            <div class="flex gap-4 items-center p-4 bg-slate-50 rounded-lg border border-slate-100">
                <select name="schedules[{{ $index }}][day_of_week]" class="rounded-lg border-slate-200">
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        <option value="{{ $day }}" {{ $schedule->day_of_week == $day ? 'selected' : '' }}>
                            {{ $day }}
                        </option>
                    @endforeach
                </select>
                
                <input type="time" name="schedules[{{ $index }}][start_time]" 
                       value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" 
                       class="rounded-lg border-slate-200">
                
                <input type="time" name="schedules[{{ $index }}][end_time]" 
                       value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}" 
                       class="rounded-lg border-slate-200">
            </div>
        @empty
            <div class="flex gap-4 items-center p-4 bg-slate-50 rounded-lg border border-slate-100">
                <select name="schedules[0][day_of_week]" class="rounded-lg border-slate-200">
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
                <input type="time" name="schedules[0][start_time]" class="rounded-lg border-slate-200">
                <input type="time" name="schedules[0][end_time]" class="rounded-lg border-slate-200">
            </div>
        @endforelse
    </div>

    <button type="button" id="add-schedule-btn" class="mt-4 text-sm text-blue-600 font-semibold hover:underline">
        + Add another day/shift
    </button>
</div>
    
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