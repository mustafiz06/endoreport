@extends('layouts.app')

@section('title', 'Schedule New Examination')
@section('page_title', 'Schedule & Intake New Examination')
@section('page_subtitle', 'Assign endoscopic procedure, endoscopist, instrument suite, and clinical indication')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('examinations.index') }}">Examinations</a></li>
    <li class="breadcrumb-item active">New Examination</li>
@endsection

@section('content')
<form action="{{ route('examinations.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-8">
            <!-- Main Details Card -->
            <div class="card card-outline card-primary shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-procedures text-primary mr-2"></i> Examination Setup
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light border">ID format: EX-{{ date('Y') }}-XXXXXX</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Patient Selection -->
                    <div class="form-group">
                        <label for="patient_id">Select Patient <span class="text-danger">*</span></label>
                        @if($selectedPatient)
                            <div class="alert alert-info py-2 px-3 mb-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-check mr-2"></i> Selected: <strong>{{ $selectedPatient->name }}</strong>
                                    <code>({{ $selectedPatient->patient_id }})</code> &bull; {{ $selectedPatient->gender }}, {{ $selectedPatient->computed_age ?? 'N/A' }}y
                                </div>
                                <a href="{{ route('examinations.create') }}" class="btn btn-xs btn-outline-dark">Change Patient</a>
                            </div>
                            <input type="hidden" name="patient_id" value="{{ $selectedPatient->id }}">
                        @else
                            <select name="patient_id" id="patient_id" class="form-control select2 @error('patient_id') is-invalid @enderror" required>
                                <option value="">-- Choose Patient from Registry --</option>
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->patient_id }} - {{ $p->name }} ({{ $p->gender }}, {{ $p->computed_age ?? 'N/A' }}y) - Phone: {{ $p->phone }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Not registered yet? <a href="{{ route('patients.create') }}" target="_blank">Register new patient here &rarr;</a></small>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>

                    <!-- Procedure Selection -->
                    <div class="form-group">
                        <label for="procedure_id">Endoscopic Procedure <span class="text-danger">*</span></label>
                        <select name="procedure_id" id="procedure_id" class="form-control select2 @error('procedure_id') is-invalid @enderror" required>
                            <option value="">-- Select Diagnostic Procedure --</option>
                            @foreach($procedures as $proc)
                                <option value="{{ $proc->id }}" {{ old('procedure_id') == $proc->id ? 'selected' : '' }}>
                                    [{{ $proc->code }}] {{ $proc->name }} (Est. {{ $proc->default_duration }} mins)
                                </option>
                            @endforeach
                        </select>
                        @error('procedure_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Clinical Indication -->
                    <div class="form-group">
                        <label for="indication">Clinical Indication / Symptoms / Reason for Procedure <span class="text-danger">*</span></label>
                        <textarea name="indication" id="indication" rows="3" class="form-control @error('indication') is-invalid @enderror" placeholder="Describe symptoms, duration, clinical suspicion, prior test results..." required>{{ old('indication') }}</textarea>
                        @error('indication')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Doctors Selection -->
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="doctor_id">Performing Endoscopist / Doctor <span class="text-danger">*</span></label>
                            <select name="doctor_id" id="doctor_id" class="form-control select2 @error('doctor_id') is-invalid @enderror" required>
                                <option value="">-- Select Performing Specialist --</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                                        {{ $doc->name }} ({{ $doc->specialization }})
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="referring_doctor_id">Referring Physician (Optional)</label>
                            <select name="referring_doctor_id" id="referring_doctor_id" class="form-control select2">
                                <option value="">-- Self / No Referral --</option>
                                @foreach($referringDoctors as $refDoc)
                                    <option value="{{ $refDoc->id }}" {{ old('referring_doctor_id') == $refDoc->id ? 'selected' : '' }}>
                                        {{ $refDoc->name }} ({{ $refDoc->hospital_chamber ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Hardware & Suite -->
                    <div class="form-group mb-0">
                        <label for="instrument_id">Video Scope / Endoscopy Suite Hardware</label>
                        <select name="instrument_id" id="instrument_id" class="form-control select2">
                            <option value="">-- Assign Later / General Equipment --</option>
                            @foreach($instruments as $inst)
                                <option value="{{ $inst->id }}" {{ old('instrument_id') == $inst->id ? 'selected' : '' }}>
                                    {{ $inst->name }} ({{ $inst->device_type }} &bull; {{ $inst->department }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Instruments can also be linked at the time of procedure capture.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Schedule & Status Card -->
            <div class="card card-outline card-info shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="far fa-calendar-alt text-info mr-2"></i> Date & Status
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="examination_date">Date of Examination <span class="text-danger">*</span></label>
                        <input type="date" name="examination_date" id="examination_date" class="form-control @error('examination_date') is-invalid @enderror" value="{{ old('examination_date', date('Y-m-d')) }}" required>
                        @error('examination_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="examination_time">Time of Examination <span class="text-danger">*</span></label>
                        <input type="time" name="examination_time" id="examination_time" class="form-control @error('examination_time') is-invalid @enderror" value="{{ old('examination_time', date('H:i')) }}" required>
                        @error('examination_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Initial Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="Scheduled" {{ old('status', 'Scheduled') === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="In Progress" {{ old('status') === 'In Progress' ? 'selected' : '' }}>In Progress (Patient in suite)</option>
                            <option value="Captured" {{ old('status') === 'Captured' ? 'selected' : '' }}>Captured (Images recorded)</option>
                            <option value="Reporting" {{ old('status') === 'Reporting' ? 'selected' : '' }}>Reporting (Transcription)</option>
                            <option value="Reviewed" {{ old('status') === 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="Finalized" {{ old('status') === 'Finalized' ? 'selected' : '' }}>Finalized (Sign-off)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="notes">Procedural Notes / Preparation Instructions</label>
                        <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') is-invalid @enderror" placeholder="Sedation choice (e.g. Midazolam + Fentanyl), fasting confirmed, vital sign observations...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <a href="{{ route('examinations.index') }}" class="btn btn-default mr-1">Cancel</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-check-circle mr-1"></i> Create Examination
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
