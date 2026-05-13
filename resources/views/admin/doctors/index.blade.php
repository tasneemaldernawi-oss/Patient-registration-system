@extends('layouts.app')

@section('content')
<div class="p-4 bg-slate-50 min-h-screen" 
     x-data="{ loading: true }" 
     x-init="setTimeout(() => loading = false, 600)">

   

    <div class="flex justify-between items-center mb-6">
        <div> 
            <h2 class="text-2xl font-bold text-slate-800">Manage Doctors</h2>
            <p class="text-sm text-slate-500">Manage and monitor all doctors.</p>
        </div>
        <a href="{{ route('doctors.create') }}" class="bg-[#1A3263] hover:bg-blue-200 text-white px-4 py-2 rounded-lg transition shadow-sm">
            + Add New Doctor
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
            <div class="h-12 bg-slate-50 border-b border-slate-200"></div>
            <div class="p-0">
                @for($i = 0; $i < 6; $i++)
                <div class="flex items-center px-6 py-4 border-b border-slate-100 gap-4">
                    <div class="h-4 bg-slate-200 rounded w-1/4"></div>
                    <div class="h-4 bg-slate-100 rounded w-1/5"></div>
                    <div class="h-4 bg-slate-100 rounded w-1/6"></div>
                    <div class="h-4 bg-slate-50 rounded w-1/6"></div>
                    <div class="h-4 bg-slate-50 rounded w-1/6"></div>
                </div>
                @endfor
            </div>
        </div>
    </div>

    <div x-show="!loading" x-cloak x-transition.opacity.duration.400ms>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse ">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Name</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Email</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Speciality</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Experience</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Address</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($doctors as $doctor)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $doctor->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-sm"> {{$doctor->email}}</td>
                            <td class="px-6 py-4 text-slate-600 text-sm"> {{$doctor->speciality}}</td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $doctor->experience }}</td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $doctor->address }}</td>
                            <td class="px-6 py-4 text-right ">
                                <div class="flex items-center justify-end gap-3 whitespace-nowrap">
                                    <a href="{{ route('doctors.edit', $doctor->id) }}" class="text-blue-700 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" class="inline mb-0 ">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 text-sm font-medium" onclick="return confirm('Are you sure you want to delete ?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                No doctors found. <a href="{{ route('doctors.create') }}" class="text-blue-600 underline">Add the first doctor</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 bg-slate-50 border-t border-slate-200">
                {{ $doctors->links() }}
            </div>
        </div>
    </div>
</div>
@endsection