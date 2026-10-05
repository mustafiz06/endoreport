@extends('layouts.app')

@section('title', 'Examination ' . $examination->examination_id)
@section('page_title', 'Clinical Examination Details')
@section('page_subtitle', 'Comprehensive procedural file, live status tracker, diagnostic report and captured media')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('examinations.index') }}">Examinations</a></li>
    <li class="breadcrumb-item active">{{ $examination->examination_id }}</li>
@endsection

@section('content')
<!-- Patient Top Bar Banner -->
<div class="card shadow-sm border-0 mb-3" style="background: linear-gradient(90deg, #1e293b 0%, #0f172a 100%); color: #fff;">
    <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <div class="rounded-circle bg-info text-white mr-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 44px; height: 44px; font-size: 1.2rem;">
                {{ strtoupper(substr($examination->patient->name, 0, 1)) }}
            </div>
            <div>
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 font-weight-bold text-white mr-2">{{ $examination->patient->name }}</h5>
                    <span class="badge badge-warning font-monospace">{{ $examination->patient->patient_id }}</span>
                </div>
                <div class="small text-light mt-1">
                    {{ $examination->patient->gender }}, {{ $examination->patient->computed_age ?? 'N/A' }} years &bull; Phone: {{ $examination->patient->phone }}
                    @if($examination->patient->blood_group)
                        &bull; Blood: <span class="badge badge-danger">{{ $examination->patient->blood_group }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <a href="{{ route('patients.show', $examination->patient) }}" class="btn btn-sm btn-outline-light font-weight-bold mr-2">
                <i class="fas fa-id-badge mr-1"></i> Patient Profile & History
            </a>
            @can('examination.edit')
                <a href="{{ route('examinations.edit', $examination) }}" class="btn btn-sm btn-light font-weight-bold">
                    <i class="fas fa-edit mr-1"></i> Edit Exam
                </a>
            @endcan
        </div>
    </div>
</div>

<!-- Workflow Status Bar -->
<div class="card shadow-sm mb-3">
    <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <span class="text-muted font-weight-bold mr-2 text-uppercase small">Current Status:</span>
            <span class="badge badge-code {{ $examination->status_badge }} px-3 py-2" style="font-size: 0.95rem;">
                {{ $examination->status }}
            </span>
            <span class="text-muted small ml-3 d-none d-lg-inline">
                Scheduled for <strong>{{ $examination->examination_date->format('M d, Y') }}</strong> at <strong>{{ date('h:i A', strtotime($examination->examination_time)) }}</strong>
            </span>
        </div>

        <!-- Quick Status Advance -->
        <div class="d-flex align-items-center">
            <form action="{{ route('examinations.status', $examination) }}" method="POST" class="form-inline">
                @csrf
                @method('PATCH')
                <label for="status" class="mr-2 small font-weight-bold text-muted">Change Workflow Status:</label>
                <select name="status" id="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                    @foreach(['Scheduled', 'In Progress', 'Captured', 'Reporting', 'Reviewed', 'Finalized', 'Cancelled'] as $st)
                        <option value="{{ $st }}" {{ $examination->status === $st ? 'selected' : '' }}>
                            {{ $st }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Examination Details & Diagnostic Report -->
    <div class="col-lg-8">
        <!-- Examination Info Card -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-file-medical text-primary mr-2"></i> Procedural Record
                </h5>
                <code class="font-weight-bold text-dark">{{ $examination->examination_id }}</code>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted font-weight-bold d-block text-uppercase">PROCEDURE</small>
                        <h6 class="font-weight-bold text-dark mb-0">{{ $examination->procedure->name }}</h6>
                        <span class="badge badge-light border">Code: {{ $examination->procedure->code }} &bull; Est. {{ $examination->procedure->default_duration }}m</span>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted font-weight-bold d-block text-uppercase">PERFORMING ENDOSCOPIST</small>
                        <h6 class="font-weight-bold text-dark mb-0">{{ $examination->doctor->name }}</h6>
                        <span class="small text-muted">{{ $examination->doctor->qualification }} ({{ $examination->doctor->specialization }})</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted font-weight-bold d-block text-uppercase">REFERRING PHYSICIAN</small>
                        <span class="text-dark">{{ $examination->referringDoctor?->name ?? 'Self-Referred / Outpatient' }}</span>
                        @if($examination->referringDoctor?->hospital_chamber)
                            <div class="small text-muted">{{ $examination->referringDoctor->hospital_chamber }}</div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted font-weight-bold d-block text-uppercase">EQUIPMENT / VIDEO SCOPE</small>
                        <span class="text-dark">{{ $examination->instrument?->name ?? 'General Suite Hardware' }}</span>
                        @if($examination->instrument)
                            <div class="small text-muted">{{ $examination->instrument->device_type }} &bull; SN: {{ $examination->instrument->serial_number ?? 'N/A' }}</div>
                        @endif
                    </div>
                </div>

                <div class="border-top pt-2">
                    <small class="text-muted font-weight-bold d-block text-uppercase">CLINICAL INDICATION</small>
                    <p class="text-dark mb-2" style="white-space: pre-line;">{{ $examination->indication }}</p>
                </div>

                @if($examination->notes)
                    <div class="border-top pt-2">
                        <small class="text-muted font-weight-bold d-block text-uppercase">PROCEDURAL / MEDICATION NOTES</small>
                        <p class="text-muted mb-0 small" style="white-space: pre-line;">{{ $examination->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Diagnostic Report Section -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-file-medical-alt text-success mr-2"></i> Diagnostic Endoscopy Report
                </h5>
                <div>
                    @if($examination->report)
                        <span class="badge badge-{{ $examination->report->status === 'Finalized' ? 'success' : 'warning' }} mr-2">
                            {{ $examination->report->status }}
                        </span>
                        <a href="{{ route('reports.show', $examination->report) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt mr-1"></i> Open Full Report
                        </a>
                        <a href="{{ route('reports.show', ['report' => $examination->report, 'print' => 1]) }}" target="_blank" class="btn btn-sm btn-outline-secondary ml-1">
                            <i class="fas fa-print mr-1"></i> Print
                        </a>
                    @else
                        @can('report.create')
                            <a href="{{ route('reports.edit', $examination) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-pen mr-1"></i> Compose Report
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($examination->report)
                    <div class="mb-3">
                        <small class="text-muted font-weight-bold d-block text-uppercase">FINDINGS</small>
                        <div class="p-3 bg-light rounded border text-dark font-monospace small" style="white-space: pre-line;">
                            {{ $examination->report->findings ?? 'No specific findings written yet.' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted font-weight-bold d-block text-uppercase">CLINICAL IMPRESSION / DIAGNOSIS</small>
                        <div class="p-2 border-left border-success bg-white font-weight-bold text-dark" style="white-space: pre-line;">
                            {{ $examination->report->impression ?? 'Pending sign-off impression.' }}
                        </div>
                    </div>

                    @if($examination->report->recommendation)
                        <div>
                            <small class="text-muted font-weight-bold d-block text-uppercase">RECOMMENDATIONS / ADVICE</small>
                            <div class="p-2 text-dark small" style="white-space: pre-line;">
                                {{ $examination->report->recommendation }}
                            </div>
                        </div>
                    @endif

                    <div class="border-top pt-2 mt-3 d-flex justify-content-between align-items-center text-muted small">
                        <span>Report Doctor: <strong>{{ $examination->report->doctor->name }}</strong></span>
                        @if($examination->report->isFinalized())
                            <span class="text-success"><i class="fas fa-check-double mr-1"></i> Finalized on {{ $examination->report->finalized_at?->format('M d, Y h:i A') }}</span>
                        @else
                            <span class="text-warning"><i class="fas fa-clock mr-1"></i> Draft Report (Not yet signed off)</span>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-file-invoice fa-2x mb-2 text-secondary d-block"></i>
                        No diagnostic report written yet for this examination.
                        @can('report.create')
                            <div class="mt-2">
                                <a href="{{ route('reports.edit', $examination) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-pen mr-1"></i> Write Clinical Report
                                </a>
                            </div>
                        @endcan
                    </div>
                @endif
            </div>
        </div>

        <!-- Captured Endoscopic Media Section (Future-Ready Architecture) -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-photo-video text-warning mr-2"></i> Captured Images & Videos
                </h5>
                <span class="badge badge-light border">
                    {{ $examination->images->count() }} Images &bull; {{ $examination->videos->count() }} Videos
                </span>
            </div>
            <div class="card-body">
                @if($examination->images->count() > 0)
                    <div class="row">
                        @foreach($examination->images as $img)
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="bg-dark text-center d-flex align-items-center justify-content-center text-white p-3" style="height: 140px;">
                                        <div>
                                            <i class="fas fa-camera fa-2x text-info mb-1"></i>
                                            <div class="small font-weight-bold">{{ $img->filename }}</div>
                                            <div class="text-muted small">Frame #{{ $img->sequence }}</div>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <small class="d-block font-weight-bold text-truncate">{{ $img->caption ?? 'Endoscopic view' }}</small>
                                        <small class="text-muted">{{ $img->captured_at?->format('h:i A') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-light border text-center py-3 mb-0">
                        <i class="fas fa-camera-retro mr-1 text-muted"></i>
                        Live video hardware capture engine is scheduled for Phase 14-16. All database models and examination relationships are connected and ready for direct frame capture.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Patient History Timeline Context -->
    <div class="col-lg-4">
        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header bg-white py-2">
                <h5 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-history text-secondary mr-2"></i> Patient's Previous Exams
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($examination->patient->examinations as $pExam)
                        <a href="{{ route('examinations.show', $pExam) }}" class="list-group-item list-group-item-action {{ $pExam->id === $examination->id ? 'bg-light font-weight-bold' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small font-monospace">{{ $pExam->examination_id }}</span>
                                <span class="badge badge-code {{ $pExam->status_badge }}">{{ $pExam->status }}</span>
                            </div>
                            <div class="text-dark small font-weight-semibold mt-1">{{ $pExam->procedure->name }}</div>
                            <div class="text-muted small">{{ $pExam->examination_date->format('M d, Y') }} &bull; {{ $pExam->doctor->name }}</div>
                        </a>
                    @empty
                        <div class="p-3 text-center text-muted small">
                            No prior examinations.
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer bg-light text-center py-2">
                <a href="{{ route('patients.show', ['patient' => $examination->patient, 'tab' => 'history']) }}" class="small font-weight-bold text-primary">
                    View Complete Longitudinal History &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
