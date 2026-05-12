@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center px-4">
    <div class="w-full max-w-md">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#1A3263] tracking-tight">Patient Registration System</h1>
            <p class="text-slate-500 mt-2">Internal Patient Management Portal</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="p-6 bg-slate-50 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-800">Admin Login</h2>
            </div>

            <form action="{{ route('login.submit') }}" method="POST" class="p-8 space-y-6">
                @csrf

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Username</label>
                    <div class="relative">
                        <input type="text" name="username" value="{{ old('username') }}" required autofocus
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                            placeholder="Enter your username">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="block text-sm font-semibold text-slate-700">Password</label>
                    </div>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                        placeholder="••••••••">
                </div>

                <button type="submit" 
                    class="w-full bg-[#1A3263] text-white py-3 rounded-lg font-bold hover:bg-blue-900 transition-colors shadow-md active:transform active:scale-[0.98]">
                    Sign In
                </button>
            </form>
        </div>

        <div class="mt-8 text-center">
            <p class="text-xs text-slate-400 uppercase tracking-widest font-semibold">
                  Username ->admin | Password -> admin123
            </p>
        </div>
    </div>
</div>
@endsection