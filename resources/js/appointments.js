window.appointmentFlow = function appointmentFlow() {
    return {
        showBooking: false,
        currentStep: 0,
        steps: [
            'Specialty',
            'Doctor',
            'Patient',
            'Appointment',
            'Confirmation'
        ],
        specialties: window.appointmentData.specialties,
        patients: window.appointmentData.patients,
        doctors: window.appointmentData.doctors,
        patientMode: 'existing',
        patientSearch: '',
        minDate: new Date().toISOString().split('T')[0],
        formData: {
            specialty_id: '',
            patient_id: '',
            doctor_id: '',
            date: '',
            time: '',
            reason: '',
            status: 'pending'
        },
        newPatient: {
            name: '',
            email: '',
            phone: '',
            date_of_birth: '',
            gender: ''
        },

        init() {
            if (window.location.hash === '#booking') {
                this.showBooking = true;
            }
        },

        toggleBooking() {
            this.showBooking = !this.showBooking;
            if (!this.showBooking) {
                this.resetForm();
            }
        },

        canProceed() {
            switch(this.currentStep) {
                case 0:
                    return this.formData.specialty_id !== '';
                case 1:
                    return this.formData.doctor_id !== '';
                case 2:
                    return this.patientMode === 'existing' ? this.formData.patient_id !== '' : this.isNewPatientValid();
                case 3:
                    return this.formData.date !== '' && this.formData.time !== '';
                case 4:
                    return true;
                default:
                    return false;
            }
        },

        filteredPatients() {
            if (!this.patientSearch) return this.patients;
            return this.patients.filter(patient => {
                const query = this.patientSearch.toLowerCase();
                return patient.name.toLowerCase().includes(query) || patient.phone.toLowerCase().includes(query);
            });
        },

        isNewPatientValid() {
            return this.newPatient.name.trim() !== '' && 
                   this.newPatient.email.trim() !== '' && 
                   this.newPatient.phone.trim() !== '' && 
                   this.newPatient.date_of_birth !== '' && 
                   (this.newPatient.gender === 'Male' || this.newPatient.gender === 'Female');
        },

        getInitials(name) {
            return name.split(' ').slice(0,2).map(part => part.charAt(0).toUpperCase()).join('');
        },

        availableDoctors() {
            return this.doctors.filter(d => d.specialty_id == this.formData.specialty_id);
        },

        selectSpecialty(id) {
            this.formData.specialty_id = id;
            this.formData.doctor_id = '';
        },

        getIcon(name) {
            const icons = {
                tooth: '🦷',
                sparkles: '✨',
                heart: '❤️',
                child: '👶',
                brain: '🧠',
            };
            return icons[name] || '⚕️';
        },

        nextStep() {
            if (this.canProceed() && this.currentStep < this.steps.length - 1) {
                this.currentStep++;
            }
        },

        previousStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },

        getPatientName() {
            if (this.patientMode === 'new' && this.newPatient.name.trim() !== '') {
                return this.newPatient.name;
            }
            const patient = this.patients.find(p => p.id == this.formData.patient_id);
            return patient ? patient.name : '';
        },

        getDoctorName() {
            const doctor = this.doctors.find(d => d.id == this.formData.doctor_id);
            return doctor ? 'Dr. ' + doctor.name : '';
        },

        getSpecialtyName() {
            const specialty = this.specialties.find(s => s.id == this.formData.specialty_id);
            return specialty ? specialty.name : '';
        },

        getDateTime() {
            if (!this.formData.date || !this.formData.time) return '';
            const dateObj = new Date(this.formData.date + 'T' + this.formData.time);
            return dateObj.toLocaleDateString('en-US', { 
                weekday: 'short', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            }) + ' at ' + this.formData.time;
        },

        resetForm() {
            this.currentStep = 0;
            this.patientMode = 'existing';
            this.patientSearch = '';
            this.formData = {
                specialty_id: '',
                patient_id: '',
                doctor_id: '',
                date: '',
                time: '',
                reason: '',
                status: 'pending'
            };
            this.newPatient = {
                name: '',
                email: '',
                phone: '',
                dob: ''
            };
        },

        async submitForm() {
            try {
                if (this.patientMode === 'new') {
                    const pForm = new FormData();
                    pForm.append('_token', document.querySelector('input[name="_token"]').value);
                    pForm.append('name', this.newPatient.name);
                    pForm.append('email', this.newPatient.email);
                    pForm.append('phone', this.newPatient.phone);
                    pForm.append('date_of_birth', this.newPatient.date_of_birth);
                    pForm.append('gender', this.newPatient.gender);
                    pForm.append('address', 'Not provided');

                    const pResp = await fetch(window.appointmentRoutes.patientsStore, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: pForm
                    });

                    if (!pResp.ok) {
                        const errorData = await pResp.json();
                        console.error('Patient create failed:', errorData);
                        
                        if (errorData.errors) {
                            const errorMessages = Object.values(errorData.errors).flat().join('\n');
                            alert('Validation errors:\n' + errorMessages);
                        } else if (errorData.message) {
                            alert('Error: ' + errorData.message);
                        } else {
                            alert('Error creating patient record');
                        }
                        return;
                    }

                    const created = await pResp.json();
                    
                    if (created.id) {
                        this.formData.patient_id = created.id;
                    } else if (created.data && created.data.id) {
                        this.formData.patient_id = created.data.id;
                    } else {
                        console.error('Unexpected response format:', created);
                        alert('Unexpected response from server');
                        return;
                    }
                }

                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('patient_id', this.formData.patient_id);
                formData.append('doctor_id', this.formData.doctor_id);
                formData.append('date', this.formData.date);
                formData.append('time', this.formData.time);
                formData.append('reason', this.formData.reason);
                formData.append('status', this.formData.status);

                const response = await fetch(window.appointmentRoutes.appointmentsStore, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    const errorData = await response.json();
                    console.error('Appointment creation failed:', errorData);
                    
                    if (errorData.errors) {
                        const errorMessages = Object.values(errorData.errors).flat().join('\n');
                        alert('Validation errors:\n' + errorMessages);
                    } else if (errorData.message) {
                        alert('Error: ' + errorData.message);
                    } else {
                        alert('Error booking appointment');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error submitting form: ' + error.message);
            }
        }
    }
}