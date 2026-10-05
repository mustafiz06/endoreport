@extends('layouts.app')

@section('title', 'Patient Profile - ' . $patient->name)
@section('page_title', 'Clinical Patient Profile')
@section('page_subtitle', 'Comprehensive longitudinal electronic medical record')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
    <li class="breadcrumb-item active">{{ $patient->patient_id }}</li>
@endsection

@section('content')
<!-- Patient Header Banner -->
<div class="patient-profile-header">
    <div class="row align-items-center">
        <div class="col-md-8 d-flex align-items-center">
            <div class="patient-avatar mr-3 shadow-sm">
                {{ strtoupper(substr($patient->name, 0, 1)) }}
            </div>
            <div>
                <div class="d-flex align-items-center flex-wrap">
                    <h2 class="font-weight-bold mb-0 text-white mr-3">{{ $patient->name }}</h2>
                    <span class="badge badge-warning font-monospace px-2 py-1 mr-2" style="font-size: 0.9rem;">
                        {{ $patient->patient_id }}
                    </span>
                    <span class="badge badge-{{ $patient->status === 'Active' ? 'success' : 'secondary' }} px-2 py-1">
                        {{ $patient->status }}
                    </span>
                </div>
                <div class="text-light small mt-2 d-flex flex-wrap align-items-center">
                    <span class="mr-3"><i class="fas fa-venus-mars mr-1 text-info"></i> {{ $patient->gender }}</span>
                    <span class="mr-3"><i class="fas fa-birthday-cake mr-1 text-info"></i> {{ $patient->computed_age ?? 'N/A' }} years ({{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'DOB N/A' }})</span>
                    <span class="mr-3"><i class="fas fa-phone mr-1 text-info"></i> {{ $patient->phone }}</span>
                    @if($patient->blood_group)
                        <span class="mr-3"><i class="fas fa-tint mr-1 text-danger"></i> Blood Group: <strong>{{ $patient->blood_group }}</strong></span>
                    @endif
                    @if($patient->national_id)
                        <span><i class="fas fa-id-card mr-1 text-info"></i> NID: {{ $patient->national_id }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-right mt-3 mt-md-0">
            @can('examination.create')
                <a href="{{ route('examinations.create', ['patient_id' => $patient->id]) }}" class="btn btn-success font-weight-bold shadow-sm mr-1">
                    <i class="fas fa-procedures mr-1"></i> New Examination
                </a>
            @endcan
            @can('patient.edit')
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline-light font-weight-bold">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            @endcan
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<div class="card card-primary card-outline card-outline-tabs shadow-sm">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="patient-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link font-weight-bold active" id="tab-overview-tab" data-toggle="pill" href="#tab-overview" role="tab">
                    <i class="fas fa-id-card-alt mr-1 text-primary"></i> Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-examinations-tab" data-toggle="pill" href="#tab-examinations" role="tab">
                    <i class="fas fa-procedures mr-1 text-info"></i> Examinations
                    <span class="badge badge-primary ml-1">{{ $patient->examinations->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-reports-tab" data-toggle="pill" href="#tab-reports" role="tab">
                    <i class="fas fa-file-medical-alt mr-1 text-success"></i> Reports
                    <span class="badge badge-success ml-1">{{ $patient->reports->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-images-tab" data-toggle="pill" href="#tab-images" role="tab">
                    <i class="fas fa-images mr-1 text-warning"></i> Images
                    <span class="badge badge-warning ml-1">{{ $patient->images->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-videos-tab" data-toggle="pill" href="#tab-videos" role="tab">
                    <i class="fas fa-video mr-1 text-danger"></i> Videos
                    <span class="badge badge-danger ml-1">{{ $patient->videos->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-history-tab" data-toggle="pill" href="#tab-history" role="tab">
                    <i class="fas fa-history mr-1 text-secondary"></i> History Timeline
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content" id="patient-tabs-content">
            
            <!-- TAB 1: OVERVIEW -->
            <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light py-2">
                                <h5 class="card-title font-weight-bold mb-0 text-dark">
                                    <i class="fas fa-user text-primary mr-2"></i> Patient Information
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered mb-0">
                                    <tr>
                                        <th class="bg-light" style="width: 35%;">Patient ID</th>
                                        <td><code class="font-weight-bold text-primary">{{ $patient->patient_id }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Full Name</th>
                                        <td>{{ $patient->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Gender / Age</th>
                                        <td>{{ $patient->gender }}, {{ $patient->computed_age ?? 'N/A' }} years old</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Date of Birth</th>
                                        <td>{{ $patient->date_of_birth ? $patient->date_of_birth->format('F d, Y') : 'Not Recorded' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">National ID / Passport</th>
                                        <td>{{ $patient->national_id ?? 'None' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Blood Group</th>
                                        <td>
                                            @if($patient->blood_group)
                                                <span class="badge badge-danger">{{ $patient->blood_group }}</span>
                                            @else
                                                <span class="text-muted">Not tested</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Registered Date</th>
                                        <td>{{ $patient->created_at->format('M d, Y h:i A') }} (by {{ $patient->creator?->name ?? 'System' }})</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Contact Card -->
                        <div class="card border mb-3">
                            <div class="card-header bg-light py-2">
                                <h5 class="card-title font-weight-bold mb-0 text-dark">
                                    <i class="fas fa-phone text-info mr-2"></i> Contact & Emergency Information
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered mb-0">
                                    <tr>
                                        <th class="bg-light" style="width: 35%;">Primary Phone</th>
                                        <td class="font-weight-bold text-dark">{{ $patient->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Email</th>
                                        <td>{{ $patient->email ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Emergency Contact</th>
                                        <td>{{ $patient->emergency_contact ?? 'Not designated' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Residential Address</th>
                                        <td>{{ $patient->address ?? 'Not recorded' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Clinical Background & Notes -->
                        <div class="card border">
                            <div class="card-header bg-light py-2">
                                <h5 class="card-title font-weight-bold mb-0 text-dark">
                                    <i class="fas fa-notes-medical text-warning mr-2"></i> Clinical Notes & Known Allergies
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($patient->notes)
                                    <p class="mb-0 text-dark" style="white-space: pre-line;">{{ $patient->notes }}</p>
                                @else
                                    <span class="text-muted small">No chronic clinical conditions or allergy notes recorded.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: EXAMINATIONS -->
            <div class="tab-pane fade" id="tab-examinations" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0">Longitudinal Examinations ({{ $patient->examinations->count() }})</h5>
                    @can('examination.create')
                        <a href="{{ route('examinations.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus mr-1"></i> Add Examination
                        </a>
                    @endcan
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped border text-nowrap">
                        <thead>
                            <tr>
                                <th>Exam ID</th>
                                <th>Procedure</th>
                                <th>Performing Doctor</th>
                                <th>Referring Doctor</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patient->examinations as $exam)
                                <tr>
                                    <td>
                                        <a href="{{ route('examinations.show', $exam) }}" class="font-weight-bold">
                                            <code>{{ $exam->examination_id }}</code>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="font-weight-semibold">{{ $exam->procedure->name }}</span>
                                        <span class="badge badge-light border ml-1">{{ $exam->procedure->code }}</span>
                                    </td>
                                    <td>{{ $exam->doctor->name }}</td>
                                    <td>{{ $exam->referringDoctor?->name ?? 'Self-Referred' }}</td>
                                    <td>
                                        <div>{{ $exam->examination_date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ date('h:i A', strtotime($exam->examination_time)) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-code {{ $exam->status_badge }}">
                                            {{ $exam->status }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('examinations.show', $exam) }}" class="btn btn-xs btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($exam->report)
                                            <a href="{{ route('reports.show', $exam->report) }}" class="btn btn-xs btn-outline-success" title="View Report">
                                                <i class="fas fa-file-alt"></i> Report
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No examinations recorded for this patient.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: REPORTS -->
            <div class="tab-pane fade" id="tab-reports" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0">Diagnostic Reports ({{ $patient->reports->count() }})</h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover border text-nowrap">
                        <thead>
                            <tr>
                                <th>Exam ID</th>
                                <th>Procedure</th>
                                <th>Endoscopist</th>
                                <th>Clinical Impression</th>
                                <th>Finalized Date</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patient->reports as $rep)
                                <tr>
                                    <td>
                                        <code>{{ $rep->examination->examination_id }}</code>
                                    </td>
                                    <td>{{ $rep->examination->procedure->name }}</td>
                                    <td>{{ $rep->doctor->name }}</td>
                                    <td class="text-truncate" style="max-width: 300px;">
                                        {{ Str::limit($rep->impression ?? 'No impression written', 60) }}
                                    </td>
                                    <td>{{ $rep->finalized_at ? $rep->finalized_at->format('M d, Y h:i A') : 'Draft' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $rep->status === 'Finalized' ? 'success' : 'warning' }}">
                                            {{ $rep->status }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('reports.show', $rep) }}" class="btn btn-xs btn-primary">
                                            <i class="fas fa-eye mr-1"></i> View Report
                                        </a>
                                        <a href="{{ route('reports.show', ['report' => $rep, 'print' => 1]) }}" target="_blank" class="btn btn-xs btn-outline-secondary">
                                            <i class="fas fa-print mr-1"></i> Print
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No diagnostic reports finalized yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 4: IMAGES -->
            <div class="tab-pane fade" id="tab-images" role="tabpanel">
                <h5 class="font-weight-bold mb-3">Captured Endoscopic Images ({{ $patient->images->count() }})</h5>
                @if($patient->images->count() > 0)
                    <div class="row">
                        @foreach($patient->images as $img)
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card h-100 border shadow-none">
                                    <div class="bg-dark text-center d-flex align-items-center justify-content-center text-light" style="height: 170px;">
                                        <div class="p-3">
                                            <i class="fas fa-camera-retro fa-3x text-info mb-2"></i>
                                            <div class="small font-weight-bold">{{ $img->filename }}</div>
                                            <div class="text-muted small">Frame #{{ $img->sequence }}</div>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <small class="d-block font-weight-bold text-dark text-truncate">{{ $img->caption ?? 'Endoscopic Capture' }}</small>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <span class="badge badge-light border small"><code>{{ $img->examination->examination_id }}</code></span>
                                            <span class="small text-muted">{{ $img->captured_at ? $img->captured_at->format('M d, Y') : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-images fa-2x mb-2 d-block text-secondary"></i>
                        No captured endoscopic images linked to this patient yet.
                    </div>
                @endif
            </div>

            <!-- TAB 5: VIDEOS -->
            <div class="tab-pane fade" id="tab-videos" role="tabpanel">
                <h5 class="font-weight-bold mb-3">Recorded Diagnostic Videos ({{ $patient->videos->count() }})</h5>
                @if($patient->videos->count() > 0)
                    <div class="row">
                        @foreach($patient->videos as $vid)
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card border">
                                    <div class="bg-dark text-center d-flex align-items-center justify-content-center text-white" style="height: 160px;">
                                        <div>
                                            <i class="fas fa-play-circle fa-3x text-danger mb-2"></i>
                                            <div class="small">{{ $vid->filename }}</div>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge badge-light border"><code>{{ $vid->examination->examination_id }}</code></span>
                                            <span class="small text-muted"><i class="fas fa-clock mr-1"></i> {{ $vid->duration ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-film fa-2x mb-2 d-block text-secondary"></i>
                        No recorded video clips associated with this patient's examinations.
                    </div>
                @endif
            </div>

            <!-- TAB 6: HISTORY (CHRONOLOGICAL) -->
            <div class="tab-pane fade" id="tab-history" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0">Longitudinal Medical & Examination Timeline</h5>
                    <span class="text-muted small">Chronological record of all endoscopic encounters</span>
                </div>

                @if($patient->examinations->count() > 0)
                    <div class="medical-timeline pl-2 mt-4">
                        @foreach($patient->examinations as $hExam)
                            <div class="medical-timeline-item">
                                <div class="medical-timeline-dot"></div>
                                <div class="card border mb-2 shadow-sm">
                                    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="font-weight-bold text-primary mr-2">
                                                <i class="far fa-calendar-check mr-1"></i> {{ $hExam->examination_date->format('l, F j, Y') }} at {{ date('h:i A', strtotime($hExam->examination_time)) }}
                                            </span>
                                            <code class="badge badge-light border">{{ $hExam->examination_id }}</code>
                                        </div>
                                        <span class="badge badge-code {{ $hExam->status_badge }}">
                                            {{ $hExam->status }}
                                        </span>
                                    </div>
                                    <div class="card-body py-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <small class="text-muted font-weight-bold d-block">PROCEDURE</small>
                                                <span class="font-weight-semibold">{{ $hExam->procedure->name }}</span>
                                                <div class="text-muted small">Code: {{ $hExam->procedure->code }}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted font-weight-bold d-block">ENDOSCOPIST / DOCTOR</small>
                                                <span>{{ $hExam->doctor->name }}</span>
                                                <div class="text-muted small">{{ $hExam->doctor->qualification }}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted font-weight-bold d-block">REFERRING DOCTOR</small>
                                                <span>{{ $hExam->referringDoctor?->name ?? 'Self / Direct Presentation' }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-2 pt-2 border-top">
                                            <small class="text-muted font-weight-bold d-block">CLINICAL INDICATION</small>
                                            <p class="mb-1 text-dark small">{{ $hExam->indication }}</p>
                                        </div>

                                        @if($hExam->report)
                                            <div class="alert alert-light border p-2 mt-2 mb-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-file-medical text-success mr-1"></i>
                                                        <strong>Report Impression:</strong>
                                                        <span class="text-dark">{{ Str::limit($hExam->report->impression, 120) }}</span>
                                                    </div>
                                                    <a href="{{ route('reports.show', $hExam->report) }}" class="btn btn-xs btn-success font-weight-bold ml-2">
                                                        View Full Report <i class="fas fa-arrow-right ml-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-light py-1 text-right small">
                                        <a href="{{ route('examinations.show', $hExam) }}" class="text-primary font-weight-bold">
                                            View Complete Examination File &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-history fa-2x mb-2 d-block text-secondary"></i>
                        No medical history entries recorded for this patient.
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Handle tab activation if tab URL parameter is present
    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var activeTab = urlParams.get('tab');
        if (activeTab) {
            $('#tab-' + activeTab + '-tab').tab('show');
        }
    });
</script>
@endpush
