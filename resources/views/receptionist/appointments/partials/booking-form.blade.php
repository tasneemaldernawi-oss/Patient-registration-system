<div x-show="showBooking" x-transition class="mb-8">
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Book New Appointment</h2>
                    <p class="text-sm text-slate-600 mt-1">Follow the steps to schedule an appointment</p>
                </div>
                <button @click="toggleBooking()" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-6 flex items-center justify-between">
                <template x-for="(step, index) in steps" :key="index">
                    <div class="flex items-center flex-1">
                        <div :class="[
                            'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition',
                            currentStep > index ? 'bg-emerald-500 text-white' : 
                            currentStep === index ? 'bg-blue-500 text-white ring-4 ring-blue-200' : 
                            'bg-slate-200 text-slate-500'
                        ]">
                            <span x-show="currentStep > index">✓</span>
                            <span x-show="currentStep <= index" x-text="index + 1"></span>
                        </div>
                        <p :class="['text-xs font-medium ml-3 flex-1', currentStep >= index ? 'text-slate-900' : 'text-slate-400']" x-text="step"></p>
                        <div x-show="index < steps.length - 1" class="flex-1 h-1 mx-2" :class="currentStep > index ? 'bg-emerald-500' : 'bg-slate-200'"></div>
                    </div>
                </template>
            </div>
        </div>

        <form @submit.prevent="submitForm()" class="p-8 space-y-6">
            @csrf

            <!-- Step 1: Select Specialty -->
            <div x-show="currentStep === 0" x-transition>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Select Medical Specialty</h3>
                        <p class="text-sm text-slate-500">Choose the clinical department required for the new appointment.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        <template x-for="specialty in specialties" :key="specialty.id">
                            <button type="button" @click="selectSpecialty(specialty.id)"
                                    :class="formData.specialty_id === specialty.id ? 'border-blue-500 bg-blue-50 shadow-sm' : 'border-slate-200 bg-white'"
                                    class="w-full p-6 text-left rounded-3xl border transition hover:border-blue-400 hover:bg-slate-50">
                                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-blue-50 text-blue-600 text-2xl">
                                    <span x-text="getIcon(specialty.icon)"></span>
                                </div>
                                <div class="mt-5">
                                    <p class="text-base font-semibold text-slate-900" x-text="specialty.name"></p>
                                    <p class="mt-3 text-sm leading-6 text-slate-500" x-text="specialty.description"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Step 2: Select Doctor -->
            <div x-show="currentStep === 1" x-transition>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Select Doctor</h3>
                        <p class="text-sm text-slate-500">Choose a provider in the selected specialty.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <template x-for="doctor in availableDoctors()" :key="doctor.id">
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:bg-slate-50"
                                   :class="formData.doctor_id == doctor.id ? 'border-blue-500 bg-blue-50' : 'border-slate-200'">
                                <input type="radio" name="doctor_id" :value="doctor.id" x-model="formData.doctor_id" class="w-4 h-4">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm ml-2 flex-shrink-0"
                                     x-text="doctor.name.charAt(0).toUpperCase()"></div>
                                <div class="ml-4">
                                    <p class="font-medium text-slate-900" x-text="'Dr. ' + doctor.name"></p>
                                    <p class="text-sm text-slate-500" x-text="doctor.speciality"></p>
                                </div>
                            </label>
                        </template>
                        <template x-if="availableDoctors().length === 0">
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                                No doctors available for the selected specialty yet.
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Step 3: Patient -->
            <div x-show="currentStep === 2" x-transition>
                <input type="hidden" name="patient_mode" :value="patientMode">
                <input type="hidden" name="patient_id" :value="formData.patient_id">
                <div class="space-y-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="space-y-2">
                            <h3 class="text-lg font-semibold text-slate-900">Patient Information Records</h3>
                            <p class="text-sm text-slate-500">Associate an existing clinical record or declare a new registration patient path.</p>
                        </div>
                        <div class="flex items-center gap-2 rounded-full bg-slate-100 p-1">
                            <button type="button" @click="patientMode = 'existing'" :class="patientMode === 'existing' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9.003 9.003 0 0112 15c2.037 0 3.918.648 5.379 1.748M15 11a3 3 0 11-6 0 3 3 0 016 0Zm6 2.25a9 9 0 11-18 0 9 9 0 0118 0Z" /></svg>
                                Existing Patient
                            </button>
                            <button type="button" @click="patientMode = 'new'" :class="patientMode === 'new' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25a3 3 0 11-6 0 3 3 0 016 0Zm-6.75 4.5a5.25 5.25 0 1110.5 0v1.5m-9.75 4.5h8.25M12 7.5v3.75" /></svg>
                                Create New Record
                            </button>
                        </div>
                    </div>
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                        <!-- Existing Patient Search -->
                        <div x-show="patientMode === 'existing'" x-transition>
                            <div class="mb-5">
                                <label class="relative block">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15Z" /></svg>
                                    </span>
                                    <input type="text" x-model="patientSearch" placeholder="Filter existing patients list by full name or telephone..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition" />
                                </label>
                            </div>
                            <div class="max-h-[420px] overflow-y-auto space-y-3">
                                <template x-if="filteredPatients().length === 0">
                                    <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">No matching patients found.</div>
                                </template>
                                <template x-for="patient in filteredPatients()" :key="patient.id">
                                    <button type="button" @click="formData.patient_id = patient.id" class="w-full rounded-3xl border p-4 text-left transition hover:border-blue-300 hover:bg-slate-50" :class="formData.patient_id == patient.id ? 'border-blue-500 bg-blue-50 shadow-sm' : 'border-slate-200 bg-white'">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                                                <span x-text="getInitials(patient.name)"></span>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-slate-900" x-text="patient.name"></p>
                                                <p class="mt-1 text-sm text-slate-500 truncate">DOB: <span x-text="patient.date_of_birth || patient.dob"></span> | Tel: <span x-text="patient.phone"></span></p>
                                            </div>
                                            <div class="flex h-9 w-9 items-center justify-center rounded-full border" :class="formData.patient_id == patient.id ? 'border-blue-500 text-blue-600' : 'border-slate-200 text-slate-500'">
                                                <svg x-show="formData.patient_id == patient.id" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                <span x-show="formData.patient_id != patient.id">+</span>
                                            </div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- New Patient Form -->
                        <div x-show="patientMode === 'new'" x-transition class="space-y-5">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-2"><label class="block text-sm font-medium text-slate-700">Full Name</label><input type="text" name="name" x-model="newPatient.name" placeholder="Jane Doe" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition" /></div>
                                <div class="space-y-2"><label class="block text-sm font-medium text-slate-700">Email</label><input type="email" name="email" x-model="newPatient.email" placeholder="jane@example.com" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition" /></div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-2"><label class="block text-sm font-medium text-slate-700">Phone Number</label><input type="tel" name="phone" x-model="newPatient.phone" placeholder="+1 (555) 349-8091" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition" /></div>
                                <div class="space-y-2"><label class="block text-sm font-medium text-slate-700">Date of Birth</label><input type="date" name="date_of_birth" x-model="newPatient.date_of_birth" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition" /></div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-slate-700">Gender</label>
                                    <select name="gender" x-model="newPatient.gender" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition">
                                        <option value="">Select gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                                <p class="font-medium text-slate-900">New patient registration preview</p>
                                <p class="mt-2">Complete the essential contact fields to create a new patient record for the appointment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Appointment Details -->
            <div x-show="currentStep === 3" x-transition>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Appointment Details</h3>
                        <p class="text-sm text-slate-500">Set the appointment time and provide any context for the visit.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2"><label class="block text-sm font-medium text-slate-700">Appointment Date</label><input type="date" name="date" x-model="formData.date" :min="minDate" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required></div>
                        <div class="space-y-2"><label class="block text-sm font-medium text-slate-700">Appointment Time</label><input type="time" name="time" x-model="formData.time" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required></div>
                        <div class="space-y-2 md:col-span-2"><label class="block text-sm font-medium text-slate-700">Reason for Visit</label><textarea name="reason" x-model="formData.reason" rows="4" placeholder="Describe the reason for this appointment..." class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"></textarea></div>
                        <div class="space-y-2 md:col-span-2"><label class="block text-sm font-medium text-slate-700">Status</label><select name="status" x-model="formData.status" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"><option value="pending">Pending</option><option value="confirmed">Confirmed</option><option value="cancelled">Cancelled</option></select></div>
                    </div>
                </div>
            </div>

            <!-- Step 5: Confirmation -->
            <div x-show="currentStep === 4" x-transition>
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-slate-900">Confirm Appointment Details</h3>
                    <div class="bg-slate-50 rounded-lg p-6 space-y-4">
                        <div class="flex justify-between items-start pb-4 border-b border-slate-200"><div><p class="text-sm text-slate-600">Specialty</p><p class="font-medium text-slate-900" x-text="getSpecialtyName()"></p></div></div>
                        <div class="flex justify-between items-start pb-4 border-b border-slate-200"><div><p class="text-sm text-slate-600">Patient</p><p class="font-medium text-slate-900" x-text="getPatientName()"></p></div></div>
                        <div class="flex justify-between items-start pb-4 border-b border-slate-200"><div><p class="text-sm text-slate-600">Doctor</p><p class="font-medium text-slate-900" x-text="getDoctorName()"></p></div></div>
                        <div class="flex justify-between items-start pb-4 border-b border-slate-200"><div><p class="text-sm text-slate-600">Date & Time</p><p class="font-medium text-slate-900" x-text="getDateTime()"></p></div></div>
                        <div class="flex justify-between items-start"><div><p class="text-sm text-slate-600">Reason for Visit</p><p class="font-medium text-slate-900" x-text="formData.reason || 'Not specified'"></p></div></div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between pt-8 border-t border-slate-200">
                <button type="button" @click="previousStep()" x-show="currentStep > 0" class="px-6 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">← Back</button>
                <div class="flex gap-3" x-show="currentStep < 4">
                    <button type="button" @click="nextStep()" :disabled="!canProceed()" :class="canProceed() ? 'bg-[#1A3263] hover:bg-blue-700 text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed'" class="px-6 py-2 rounded-lg transition font-medium shadow-sm">Next Step →</button>
                </div>
                <div class="flex gap-3" x-show="currentStep === 4">
                    <button type="button" @click="previousStep()" class="px-6 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">← Back</button>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg transition font-medium shadow-sm">Confirm & Book</button>
                </div>
            </div>
        </form>
    </div>
</div>