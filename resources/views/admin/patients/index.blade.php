@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Manage Patients</h2>
    <a href="{{ route('patients.create') }}" class="bg-[#1A3263] hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-sm">
        + Add New Patient
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Name</th>
                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Age</th>
                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Phone Number</th>
                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Email</th>
                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Address</th>
                <th class="px-6 py-4 text-sm font-semibold text-slate-600 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($patients as $patient)
            <tr class="hover:bg-slate-50 transition">
                <td class="px-6 py-4">
                    <div class="font-medium text-slate-900">{{ $patient->name }}</div>
                </td>
                <td class="px-6 py-4 text-sm">
                    @if($patient->dob)
                        <span class="text-slate-900">{{ $patient->dob->age ?? 'N/A' }} years</span>
                        <p>{{ $patient->dob->format('M d, Y') }}</p>
                    @else
                       <span class="text-slate-400 italic">No date provided</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-slate-600 text-sm"> {{$patient->phone}}</td>
                <td class="px-6 py-4 text-slate-600 text-sm"> {{$patient->address}}</td>
                <td class="px-6 py-4 text-slate-600 text-sm">{{ $patient->email }}</td>
                
                <td class="px-6 py-4 text-right ">
                    <div class="flex items-center justify-end gap-3 whitespace-nowrap">
                    <a href="{{ route('patients.edit', $patient->id) }}" class="text-blue-700 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>

                    </a>
                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="inline mb-0 ">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-rose-500 hover:text-rose-700 text-sm font-medium" onclick="return confirm('Are you sure?')">
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
                <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                    No patients found. <a href="{{ route('patients.create') }}" class="text-blue-600 underline">Add the first patient</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-4 bg-slate-50 border-t border-slate-200">
        {{ $patients->links() }}
    </div>
</div>
@endsection