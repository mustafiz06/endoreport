@extends('layouts.app')

@section('title', 'Patient History Explorer')
@section('page_title', 'Longitudinal Patient History & Examination Drill-Down')
@section('page_subtitle', 'Chronological medical records, prior endoscopy reports, and longitudinal media comparison')

@section('breadcrumb')
    <li class="breadcrumb-item active">Patient History</li>
@endsection

@section('content')
<!-- Patient Search & Selection Card -->
<div class="card shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('patient-history.index') }}" class="form-row align-items-center">
            <div class="col-md-8 mb-2 mb-md-0">
                <label for="patient_id" class="small text-muted font-weight-bold mb-1">SELECT PATIENT TO EXPLORE HISTORY</label>
                <select name="patient_id" id="patient_id" class="form-control select2" onchange="this.form.submit()">
                    <option value="">-- Search & Choose Patient by Name, ID, or Phone --</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}" {{ $selectedPatient && $selectedPatient->id === $p->id ? 'selected' : '' }}>
                            {{ $p->patient_id }} - {{ $p->name }} ({{ $p->gender }}, {{ $p->computed_age ?? 'N/A' }}y) &bull; {{ $p->examinations_count }} Examinations &bull; Phone: {{ $p->phone }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 text-md-right mt-md-4">
                @if($selectedPatient)
                    <a href="{{ route('patients.show', $selectedPatient) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-id-card mr-1"></i> Patient File
                    </a>
                    @can('examination.create')
                        <a href="{{ route('examinations.create', ['patient_id' => $selectedPatient->id]) }}" class="btn btn-success btn-sm ml-1 font-weight-bold">
                            <i class="fas fa-plus mr-1"></i> New Exam
                        </a>
                    @endcan
                @endif
            </div>
        </form>
    </div>
</div>

@if($selectedPatient)
    <!-- Selected Patient Summary Banner -->
    <div class="patient-profile-header mb-3 py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="patient-avatar mr-3 shadow-sm" style="width: 50px; height: 50px; font-size: 1.3rem;">
                    {{ strtoupper(substr($selectedPatient->name, 0, 1)) }}
                </div>
                <div>
                    <h4 class="font-weight-bold text-white mb-0">{{ $selectedPatient->name }}</h4>
                    <div class="text-light small mt-1">
                        <code class="text-warning font-weight-bold">{{ $selectedPatient->patient_id }}</code> &bull;
                        {{ $selectedPatient->gender }}, {{ $selectedPatient->computed_age ?? 'N/A' }} yrs &bull;
                        Phone: {{ $selectedPatient->phone }} &bull;
                        Blood Group: <strong>{{ $selectedPatient->blood_group ?? 'Unknown' }}</strong>
                    </div>
                </div>
            </div>
            <div>
                <span class="badge badge-light px-3 py-2 font-weight-bold">
                    {{ $historyExaminations->count() }} Recorded Examination(s)
                </span>
            </div>
        </div>
    </div>

    <!-- Patient History Table & Drill-Down -->
    <div class="row">
        <!-- Examination List Column -->
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-2">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-list-ol text-primary mr-2"></i> Chronological History
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="exam-history-list" role="tablist">
                        @forelse($historyExaminations as $index => $exam)
                            <a class="list-group-item list-group-item-action {{ $index === 0 ? 'active' : '' }}" id="exam-item-{{ $exam->id }}" data-toggle="list" href="#exam-detail-{{ $exam->id }}" role="tab">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="font-weight-bold">{{ $exam->examination_date->format('M d, Y') }}</span>
                                    <span class="badge badge-code {{ $exam->status_badge }}">{{ $exam->status }}</span>
                                </div>
                                <div class="small font-weight-semibold text-truncate mb-1">{{ $exam->procedure->name }}</div>
                                <div class="d-flex justify-content-between align-items-center small text-muted">
                                    <span><code>{{ $exam->examination_id }}</code></span>
                                    <span>{{ $exam->doctor->name }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-info-circle fa-2x mb-2 text-secondary d-block"></i>
                                No previous examinations on record for this patient.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Examination Drill-Down Detail Column -->
        <div class="col-lg-7">
            <div class="tab-content" id="nav-tabContent">
                @forelse($historyExaminations as $index => $exam)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="exam-detail-{{ $exam->id }}" role="tabpanel">
                        <!-- Examination Detail Card -->
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                <h5 class="card-title font-weight-bold mb-0 text-dark">
                                    <i class="fas fa-file-medical text-primary mr-2"></i> Examination File: <code>{{ $exam->examination_id }}</code>
                                </h5>
                                <a href="{{ route('examinations.show', $exam) }}" class="btn btn-xs btn-outline-primary" target="_blank">
                                    <i class="fas fa-external-link-alt mr-1"></i> Full Workstation View
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <small class="text-muted font-weight-bold d-block">PROCEDURE</small>
                                        <span class="font-weight-bold text-dark">{{ $exam->procedure->name }}</span> ({{ $exam->procedure->code }})
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted font-weight-bold d-block">PERFORMING SPECIALIST</small>
                                        <span class="font-weight-bold text-dark">{{ $exam->doctor->name }}</span>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <small class="text-muted font-weight-bold d-block">EXAMINATION DATE & TIME</small>
                                        <span>{{ $exam->examination_date->format('F d, Y') }} at {{ date('h:i A', strtotime($exam->examination_time)) }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted font-weight-bold d-block">REFERRING DOCTOR</small>
                                        <span>{{ $exam->referringDoctor?->name ?? 'Self-Referred' }}</span>
                                    </div>
                                </div>

                                <div class="border-top pt-2 mt-2">
                                    <small class="text-muted font-weight-bold d-block">CLINICAL INDICATION</small>
                                    <p class="text-dark small mb-0">{{ $exam->indication }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Report Card -->
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                <h6 class="font-weight-bold mb-0 text-success">
                                    <i class="fas fa-file-medical-alt mr-1"></i> Diagnostic Report
                                </h6>
                                @if($exam->report)
                                    <span class="badge badge-{{ $exam->report->status === 'Finalized' ? 'success' : 'warning' }}">
                                        {{ $exam->report->status }}
                                    </span>
                                @endif
                            </div>
                            <div class="card-body">
                                @if($exam->report)
                                    <div class="mb-2">
                                        <small class="text-muted font-weight-bold d-block">FINDINGS</small>
                                        <div class="p-2 bg-light rounded border text-dark font-monospace small" style="white-space: pre-line;">
                                            {{ $exam->report->findings ?? 'No findings noted.' }}
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted font-weight-bold d-block">IMPRESSION</small>
                                        <div class="p-2 border-left border-success bg-white font-weight-bold text-dark small" style="white-space: pre-line;">
                                            {{ $exam->report->impression ?? 'No impression documented.' }}
                                        </div>
                                    </div>
                                    @if($exam->report->recommendation)
                                        <div>
                                            <small class="text-muted font-weight-bold d-block">RECOMMENDATIONS</small>
                                            <p class="small text-dark mb-0">{{ $exam->report->recommendation }}</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-3 text-muted small">
                                        <i class="fas fa-file-invoice text-secondary mr-1"></i> No report attached yet.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Media Captures Card -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-white py-2">
                                <h6 class="font-weight-bold mb-0 text-warning">
                                    <i class="fas fa-images mr-1"></i> Captured Images & Media ({{ $exam->images->count() }})
                                </h6>
                            </div>
                            <div class="card-body">
                                @if($exam->images->count() > 0)
                                    <div class="row">
                                        @foreach($exam->images as $img)
                                            <div class="col-sm-6 mb-2">
                                                <div class="border rounded p-2 text-center bg-light">
                                                    <i class="fas fa-camera text-info mb-1"></i>
                                                    <div class="small font-weight-bold">{{ $img->filename }}</div>
                                                    <small class="text-muted">{{ $img->caption ?? 'Frame #' . $img->sequence }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3 text-muted small">
                                        <i class="fas fa-camera text-secondary mr-1"></i> No images captured for this examination.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 text-secondary"></i>
                            <h5>No Examination History Available</h5>
                            <p class="small">This patient does not have any recorded examinations in the system.</p>
                            @can('examination.create')
                                <a href="{{ route('examinations.create', ['patient_id' => $selectedPatient->id]) }}" class="btn btn-primary btn-sm font-weight-bold">
                                    <i class="fas fa-plus mr-1"></i> Schedule First Examination
                                </a>
                            @endcan
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@else
    <!-- Prompt to Select Patient -->
    <div class="card shadow-sm text-center py-5">
        <div class="card-body">
            <i class="fas fa-search-plus fa-3x text-primary mb-3"></i>
            <h4 class="font-weight-bold text-dark">Select a Patient to View Historical Timeline</h4>
            <p class="text-muted" style="max-width: 500px; margin: 0 auto;">
                Use the search dropdown above to select any patient. You will see their complete chronological history of endoscopic examinations, reports, findings, and captured media.
            </p>
        </div>
    </div>
@endif
@endsection
