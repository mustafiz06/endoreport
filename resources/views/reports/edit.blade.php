@extends('layouts.app')

@section('title', 'Write Report - ' . $examination->examination_id)
@section('page_title', 'Diagnostic Report Composer')
@section('page_subtitle', 'Structured report transcription, endoscopic findings documentation, and impression sign-off')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item"><a href="{{ route('examinations.show', $examination) }}">{{ $examination->examination_id }}</a></li>
    <li class="breadcrumb-item active">Compose Report</li>
@endsection

@section('content')
<form action="{{ route('reports.update', $examination) }}" method="POST" id="report-form">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Main Report Fields -->
        <div class="col-lg-8">
            <!-- Patient and Exam Context Banner -->
            <div class="card bg-white shadow-sm mb-3">
                <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block">PATIENT & PROCEDURE</span>
                        <h5 class="font-weight-bold mb-0 text-dark">
                            {{ $examination->patient->name }} &bull; <span class="text-primary">{{ $examination->procedure->name }}</span>
                        </h5>
                        <div class="small text-muted mt-1">
                            <code>{{ $examination->patient->patient_id }}</code> &bull; Exam: <code>{{ $examination->examination_id }}</code> &bull; Date: {{ $examination->examination_date->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <span class="badge badge-code {{ $examination->status_badge }}">Status: {{ $examination->status }}</span>
                    </div>
                </div>
            </div>

            <!-- Findings Card -->
            <div class="card card-outline card-primary shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-microscope text-primary mr-2"></i> 1. Detailed Endoscopic Findings <span class="text-danger">*</span>
                    </h3>
                    <button type="button" class="btn btn-xs btn-outline-secondary" onclick="insertTemplate()">
                        <i class="fas fa-undo mr-1"></i> Reset to Default Template
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group mb-0">
                        <textarea name="findings" id="findings" rows="9" class="form-control font-monospace @error('findings') is-invalid @enderror" placeholder="Describe mucosal appearance, lumens, anatomical landmarks, polyps, erosions, bleeding..." required>{{ old('findings', $defaultFindings) }}</textarea>
                        @error('findings')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Clinical Impression & Recommendation -->
            <div class="card card-outline card-success shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-check-circle text-success mr-2"></i> 2. Clinical Impression & Recommendation
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="impression">Impression / Definitive Diagnosis <span class="text-danger">*</span></label>
                        <textarea name="impression" id="impression" rows="3" class="form-control font-weight-bold @error('impression') is-invalid @enderror" placeholder="Primary diagnostic impression (e.g. 1. Grade B Reflux Esophagitis. 2. Duodenal ulcer S1)..." required>{{ old('impression', $defaultImpression) }}</textarea>
                        @error('impression')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="recommendation">Recommendations & Clinical Advice</label>
                        <textarea name="recommendation" id="recommendation" rows="3" class="form-control @error('recommendation') is-invalid @enderror" placeholder="Post-procedure medication, dietary advice, biopsy follow-up, repeat surveillance date...">{{ old('recommendation', $defaultRecommendation) }}</textarea>
                        @error('recommendation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side Settings & Sign-off -->
        <div class="col-lg-4">
            <!-- Sign-Off Doctor & Status -->
            <div class="card card-outline card-warning shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-signature text-warning mr-2"></i> Report Sign-off
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="doctor_id">Reporting Doctor <span class="text-danger">*</span></label>
                        <select name="doctor_id" id="doctor_id" class="form-control select2 @error('doctor_id') is-invalid @enderror" required>
                            @foreach($doctors as $doc)
                                <option value="{{ $doc->id }}" {{ old('doctor_id', $report?->doctor_id ?? $examination->doctor_id) == $doc->id ? 'selected' : '' }}>
                                    {{ $doc->name }} ({{ $doc->specialization }})
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status_select">Report Status <span class="text-danger">*</span></label>
                        <select name="status" id="status_select" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="Draft" {{ old('status', $report?->status ?? 'Draft') === 'Draft' ? 'selected' : '' }}>
                                Draft (Still editing / Incomplete)
                            </option>
                            @can('report.finalize')
                                <option value="Finalized" {{ old('status', $report?->status) === 'Finalized' ? 'selected' : '' }}>
                                    Finalized (Sign off & lock)
                                </option>
                            @endcan
                        </select>
                        <small class="text-muted d-block mt-1">
                            Selecting "Finalized" permanently locks the report and advances the examination to Finalized.
                        </small>
                    </div>

                    <div class="alert alert-light border small text-muted">
                        <i class="fas fa-info-circle text-info mr-1"></i> Once finalized, electronic signature and official credentials will appear on the printed medical sheet.
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <button type="submit" onclick="document.getElementById('status_select').value='Draft';" class="btn btn-outline-secondary btn-block font-weight-bold mb-2">
                        <i class="fas fa-save mr-1"></i> Save as Draft
                    </button>
                    @can('report.finalize')
                        <button type="submit" onclick="document.getElementById('status_select').value='Finalized';" class="btn btn-success btn-block font-weight-bold shadow-sm">
                            <i class="fas fa-check-double mr-1"></i> Finalize & Sign Off
                        </button>
                    @endcan
                </div>
            </div>

            <!-- Clinical Indication Reference -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-2">
                    <h6 class="font-weight-bold mb-0 text-muted">
                        <i class="fas fa-notes-medical mr-1"></i> Examination Indication
                    </h6>
                </div>
                <div class="card-body py-2">
                    <p class="small text-dark mb-0">{{ $examination->indication }}</p>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var templateText = @json($examination->procedure->default_report_template ?? '');
    function insertTemplate() {
        if (confirm('Overwrite current findings with default procedural template?')) {
            document.getElementById('findings').value = templateText;
        }
    }
</script>
@endsection
