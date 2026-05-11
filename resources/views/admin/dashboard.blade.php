@extends('layouts.app')

@section('content')
 <div class="flex justify-between items-center p-6">
                    <div>
                        <h2 class="text-2xl font-bold">Dashboard</h2>
                        <p class="text-blue-900 text-sm">Welcome back, Admin</p>
                    </div>
     </div>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-sm flex justify-between items-center">
        <div>
            <p class="text-gray-400 text-sm font-medium">Total Patients</p>
            <h3 class="text-2xl font-bold text-slate-800">1,247</h3>
        </div>
        <div class="bg-blue-500 p-3 rounded-lg text-white">👥</div>
    </div>

    </div>

<div class="mt-8 bg-white p-6 rounded-xl shadow-sm">
    <h3 class="font-bold text-lg mb-4">Upcoming Appointments</h3>
    <p class="text-gray-500">No appointments for today.</p>
</div>

@endsection