@extends('layouts.app')

@section('title', 'Register New Patient')
@section('page_title', 'Register New Patient')
@section('page_subtitle', 'Intake form for new patient registration and clinical file creation')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
    <li class="breadcrumb-item active">New Patient</li>
@endsection

@section('content')
<form action="{{ route('patients.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-8">
            <!-- 1. Demographics Card -->
            <div class="card card-outline card-primary shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-circle text-primary mr-2"></i> 1. Patient Demographics
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light border">Patient ID will be auto-generated</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="name">Full Patient Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Johnathan Doe" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d') }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="age">Age (Years)</label>
                            <input type="number" name="age" id="age" class="form-control @error('age') is-invalid @enderror" value="{{ old('age') }}" min="0" max="130" placeholder="Auto-calculated from DOB">
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="gender">Gender <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="national_id">National ID / Passport / SSN</label>
                            <input type="text" name="national_id" id="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id') }}" placeholder="e.g. NID-8819203">
                            @error('national_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="blood_group">Blood Group</label>
                            <select name="blood_group" id="blood_group" class="form-control @error('blood_group') is-invalid @enderror">
                                <option value="">Unknown / Not Tested</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
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
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="patient@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="emergency_contact">Emergency Contact Person & Phone</label>
                        <input type="text" name="emergency_contact" id="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" value="{{ old('emergency_contact') }}" placeholder="e.g. Mary Doe (Spouse) - +1 (555) 999-1122">
                        @error('emergency_contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="address">Residential Address</label>
                        <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror" placeholder="Full street, city, state, postal code">{{ old('address') }}</textarea>
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
                            <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="Archived" {{ old('status') === 'Archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        <small class="text-muted d-block mt-1">Active patients can be scheduled for procedures.</small>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="notes">Clinical Background / Known Allergies</label>
                        <textarea name="notes" id="notes" rows="6" class="form-control @error('notes') is-invalid @enderror" placeholder="Mention chronic conditions (e.g. GERD, hypertension, diabetes), prior surgeries, or drug allergies...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <a href="{{ route('patients.index') }}" class="btn btn-default mr-1">Cancel</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Register Patient
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Auto-calculate age on DOB change
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
