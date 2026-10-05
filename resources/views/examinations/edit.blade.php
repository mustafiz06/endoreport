@extends('layouts.app')

@section('title', 'Edit Examination ' . $examination->examination_id)
@section('page_title', 'Edit Examination Details')
@section('page_subtitle', 'Update clinical indication, doctor assignments, and procedural status for ' . $examination->examination_id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('examinations.index') }}">Examinations</a></li>
    <li class="breadcrumb-item"><a href="{{ route('examinations.show', $examination) }}">{{ $examination->examination_id }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<form action="{{ route('examinations.update', $examination) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-procedures text-primary mr-2"></i> Examination Configuration: <code>{{ $examination->examination_id }}</code>
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Patient Summary (Read-Only) -->
                    <div class="alert alert-light border d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-muted small d-block">PATIENT</span>
                            <span class="font-weight-bold text-dark">{{ $examination->patient->name }}</span>
                            <code>({{ $examination->patient->patient_id }})</code> &bull; {{ $examination->patient->gender }}, {{ $examination->patient->computed_age ?? 'N/A' }}y
                        </div>
                        <a href="{{ route('patients.show', $examination->patient) }}" class="btn btn-xs btn-outline-primary" target="_blank">
                            View Patient File &rarr;
                        </a>
                    </div>

                    <!-- Procedure Selection -->
                    <div class="form-group">
                        <label for="procedure_id">Endoscopic Procedure <span class="text-danger">*</span></label>
                        <select name="procedure_id" id="procedure_id" class="form-control select2 @error('procedure_id') is-invalid @enderror" required>
                            @foreach($procedures as $proc)
                                <option value="{{ $proc->id }}" {{ old('procedure_id', $examination->procedure_id) == $proc->id ? 'selected' : '' }}>
                                    [{{ $proc->code }}] {{ $proc->name }} (Est. {{ $proc->default_duration }} mins)
                                </option>
                            @endforeach
                        </select>
                        @error('procedure_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Indication -->
                    <div class="form-group">
                        <label for="indication">Clinical Indication / Symptoms <span class="text-danger">*</span></label>
                        <textarea name="indication" id="indication" rows="3" class="form-control @error('indication') is-invalid @enderror" required>{{ old('indication', $examination->indication) }}</textarea>
                        @error('indication')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Doctors -->
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="doctor_id">Performing Endoscopist / Doctor <span class="text-danger">*</span></label>
                            <select name="doctor_id" id="doctor_id" class="form-control select2 @error('doctor_id') is-invalid @enderror" required>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" {{ old('doctor_id', $examination->doctor_id) == $doc->id ? 'selected' : '' }}>
                                        {{ $doc->name }} ({{ $doc->specialization }})
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="referring_doctor_id">Referring Physician</label>
                            <select name="referring_doctor_id" id="referring_doctor_id" class="form-control select2">
                                <option value="">-- Self / Direct --</option>
                                @foreach($referringDoctors as $refDoc)
                                    <option value="{{ $refDoc->id }}" {{ old('referring_doctor_id', $examination->referring_doctor_id) == $refDoc->id ? 'selected' : '' }}>
                                        {{ $refDoc->name }} ({{ $refDoc->hospital_chamber ?? 'Chamber' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Instrument -->
                    <div class="form-group mb-0">
                        <label for="instrument_id">Video Scope / Hardware Suite</label>
                        <select name="instrument_id" id="instrument_id" class="form-control select2">
                            <option value="">-- None / General Equipment --</option>
                            @foreach($instruments as $inst)
                                <option value="{{ $inst->id }}" {{ old('instrument_id', $examination->instrument_id) == $inst->id ? 'selected' : '' }}>
                                    {{ $inst->name }} ({{ $inst->device_type }} &bull; {{ $inst->department }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-outline card-info shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="far fa-calendar-alt text-info mr-2"></i> Timing & Status
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="examination_date">Date of Examination <span class="text-danger">*</span></label>
                        <input type="date" name="examination_date" id="examination_date" class="form-control @error('examination_date') is-invalid @enderror" value="{{ old('examination_date', $examination->examination_date->format('Y-m-d')) }}" required>
                        @error('examination_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="examination_time">Time of Examination <span class="text-danger">*</span></label>
                        <input type="time" name="examination_time" id="examination_time" class="form-control @error('examination_time') is-invalid @enderror" value="{{ old('examination_time', date('H:i', strtotime($examination->examination_time))) }}" required>
                        @error('examination_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Examination Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            @foreach(['Scheduled', 'In Progress', 'Captured', 'Reporting', 'Reviewed', 'Finalized', 'Cancelled'] as $st)
                                <option value="{{ $st }}" {{ old('status', $examination->status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="notes">Procedural Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $examination->notes) }}</textarea>
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <a href="{{ route('examinations.show', $examination) }}" class="btn btn-default mr-1">Cancel</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Update Examination
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
