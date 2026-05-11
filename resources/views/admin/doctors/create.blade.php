@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-800">Doctor Information</h2>
            <p class="text-sm text-slate-500">Fill in the details to register a new doctor in the system.</p>
        </div>

        <form action="{{ route('doctors.store') }}" method="POST" class="p-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="e.g. John Doe">
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
                    <input type="email" name="email" id="email" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="john@example.com">

                        <!--to validate if the user already exist and show a messge to admin -->

                        @error('email')
                           <p class="text-xs text-rose-500 mt-1">{{$message}}</p>
                        @enderror
                </div>

                 <!-- don't forget to work on phone number field -->

                <div class="space-y-2">
                    <label for="speciality" class="block text-sm font-semibold text-slate-700">Speciality</label>
                    <input type="text" name="speciality" id="speciality" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="e.g. dentist">
                </div>
                <div class="space-y-2">
                    <label for="experience" class="block text-sm font-semibold text-slate-700">Experience</label>
                    <input type="text" name="experience" id="experience" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>
                

                <div class="space-y-2">
                    <label for="address" class="block text-sm font-semibold text-slate-700">Address</label>
                    <input type="text" name="address" id="address" 
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('doctors.index') }}" 
                   class="px-6 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 rounded-lg bg-[#1A3263] text-white hover:bg-blue-200 transition font-medium shadow-sm">
                    Save Doctor

                </button>
            </div>
        </form>
    </div>
</div>
@endsection