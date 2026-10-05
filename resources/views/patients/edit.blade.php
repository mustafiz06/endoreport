@extends('layouts.app')

@section('title', 'Edit Patient ' . $patient->patient_id)
@section('page_title', 'Edit Patient Details')
@section('page_subtitle', 'Update demographic and clinical details for ' . $patient->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
    <li class="breadcrumb-item"><a href="{{ route('patients.show', $patient) }}">{{ $patient->patient_id }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<form action="{{ route('patients.update', $patient) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-8">
            <!-- 1. Demographics Card -->
            <div class="card card-outline card-primary shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-circle text-primary mr-2"></i> 1. Patient Demographics
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light border font-weight-bold font-monospace">{{ $patient->patient_id }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="name">Full Patient Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name', $patient->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="age">Age (Years)</label>
                            <input type="number" name="age" id="age" class="form-control @error('age') is-invalid @enderror" value="{{ old('age', $patient->age) }}" min="0" max="130">
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="gender">Gender <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="Male" {{ old('gender', $patient->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $patient->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $patient->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="national_id">National ID / Passport / SSN</label>
                            <input type="text" name="national_id" id="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id', $patient->national_id) }}">
                            @error('national_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="blood_group">Blood Group</label>
                            <select name="blood_group" id="blood_group" class="form-control @error('blood_group') is-invalid @enderror">
                                <option value="">Unknown / Not Tested</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group', $patient->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Contact Information Card -->
            <div class="card card-outline card-info shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-address-book text-info mr-2"></i> 2. Contact & Emergency Details
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="phone">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $patient->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $patient->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="emergency_contact">Emergency Contact Person & Phone</label>
                        <input type="text" name="emergency_contact" id="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" value="{{ old('emergency_contact', $patient->emergency_contact) }}">
                        @error('emergency_contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="address">Residential Address</label>
                        <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address', $patient->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- 3. Status & Clinical Notes -->
            <div class="card card-outline card-secondary shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-clipboard-check text-secondary mr-2"></i> 3. Status & Record Notes
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="status">Patient Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="Active" {{ old('status', $patient->status) === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $patient->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="Archived" {{ old('status', $patient->status) === 'Archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        <small class="text-muted d-block mt-1">If patient cannot be deleted due to medical history, archive here.</small>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="notes">Clinical Background / Known Allergies</label>
                        <textarea name="notes" id="notes" rows="6" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $patient->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <a href="{{ route('patients.show', $patient) }}" class="btn btn-default mr-1">Cancel</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </div>

            <!-- Danger Zone: Medical History Notice -->
            <div class="card card-outline card-danger shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold text-danger">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Medical Record Governance
                    </h3>
                </div>
                <div class="card-body text-sm">
                    @if($patient->hasMedicalHistory())
                        <div class="alert alert-warning py-2 px-3 small mb-0">
                            <i class="fas fa-shield-alt mr-1"></i> <strong>Medical History Protected:</strong> This patient has {{ $patient->examinations->count() }} clinical examination(s) on file. In compliance with medical record regulations, permanent deletion is disabled.
                        </div>
                    @else
                        <p class="text-muted small">No examinations recorded yet for this patient. You may delete this record if created by mistake.</p>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-block" onclick="if(confirm('Are you sure you want to delete this patient record?')) document.getElementById('delete-patient-form').submit();">
                            <i class="fas fa-trash-alt mr-1"></i> Delete Patient Record
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

@if(!$patient->hasMedicalHistory())
    <form id="delete-patient-form" action="{{ route('patients.destroy', $patient) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endif

@push('scripts')
<script>
    document.getElementById('date_of_birth').addEventListener('change', function() {
        var dob = new Date(this.value);
        if (!isNaN(dob.getTime())) {
            var diff_ms = Date.now() - dob.getTime();
            var age_dt = new Date(diff_ms);
            var age = Math.abs(age_dt.getUTCFullYear() - 1970);
            document.getElementById('age').value = age;
        }
    });
</script>
@endpush
@endsection
