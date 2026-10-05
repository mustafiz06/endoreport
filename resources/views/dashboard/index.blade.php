@extends('layouts.app')

@section('title', 'Clinical Workstation Dashboard')
@section('page_title', 'Endoscopy & Diagnostic Workstation')
@section('page_subtitle', 'Live clinical statistics, examination schedule, and pending reporting queue')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Quick Action Bar -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card bg-white shadow-sm border-0 mb-3">
            <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <span class="badge badge-light border text-dark py-2 px-3 mr-3">
                        <i class="far fa-calendar-alt text-primary mr-1"></i> Today: <strong>{{ date('l, F j, Y') }}</strong>
                    </span>
                    <span class="text-muted small d-none d-lg-inline">
                        <i class="fas fa-check-circle text-success mr-1"></i> System Status: <strong>Online & Calibrated</strong>
                    </span>
                </div>
                <div class="btn-group">
                    @can('patient.create')
                        <a href="{{ route('patients.create') }}" class="btn btn-outline-primary btn-sm font-weight-bold">
                            <i class="fas fa-user-plus mr-1"></i> New Patient
                        </a>
                    @endcan
                    @can('examination.create')
                        <a href="{{ route('examinations.create') }}" class="btn btn-primary btn-sm font-weight-bold ml-2">
                            <i class="fas fa-procedures mr-1"></i> New Examination
                        </a>
                    @endcan
                    @can('patient.view')
                        <a href="{{ route('patient-history.index') }}" class="btn btn-outline-dark btn-sm font-weight-bold ml-2">
                            <i class="fas fa-history mr-1"></i> Patient History
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Stat Cards (6 Cards) -->
<div class="row">
    <!-- Today's Patients -->
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="small-box bg-white border">
            <div class="inner">
                <h3 class="text-info font-weight-bold">{{ $todayPatientsCount }}</h3>
                <p class="text-muted">Today's Patients</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-clock text-info"></i>
            </div>
            <a href="{{ route('patients.index') }}" class="small-box-footer text-muted bg-light border-top">
                View Patients <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Today's Examinations -->
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="small-box bg-white border">
            <div class="inner">
                <h3 class="text-primary font-weight-bold">{{ $todayExaminationsCount }}</h3>
                <p class="text-muted">Today's Exams</p>
            </div>
            <div class="icon">
                <i class="fas fa-stethoscope text-primary"></i>
            </div>
            <a href="{{ route('examinations.index') }}" class="small-box-footer text-muted bg-light border-top">
                Today's Schedule <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Pending Reports -->
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="small-box bg-white border">
            <div class="inner">
                <h3 class="text-warning font-weight-bold">{{ $pendingReportsCount }}</h3>
                <p class="text-muted">Pending Reports</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-signature text-warning"></i>
            </div>
            <a href="{{ route('reports.index', ['filter' => 'pending']) }}" class="small-box-footer text-muted bg-light border-top">
                Reporting Queue <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Finalized Reports -->
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="small-box bg-white border">
            <div class="inner">
                <h3 class="text-success font-weight-bold">{{ $finalizedReportsCount }}</h3>
                <p class="text-muted">Finalized Reports</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-double text-success"></i>
            </div>
            <a href="{{ route('reports.index', ['filter' => 'finalized']) }}" class="small-box-footer text-muted bg-light border-top">
                Verified Reports <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Total Patients -->
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="small-box bg-white border">
            <div class="inner">
                <h3 class="text-secondary font-weight-bold">{{ $totalPatientsCount }}</h3>
                <p class="text-muted">Total Patients</p>
            </div>
            <div class="icon">
                <i class="fas fa-users text-secondary"></i>
            </div>
            <a href="{{ route('patients.index') }}" class="small-box-footer text-muted bg-light border-top">
                All Registry <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Total Examinations -->
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="small-box bg-white border">
            <div class="inner">
                <h3 class="text-dark font-weight-bold">{{ $totalExaminationsCount }}</h3>
                <p class="text-muted">Total Exams</p>
            </div>
            <div class="icon">
                <i class="fas fa-notes-medical text-dark"></i>
            </div>
            <a href="{{ route('examinations.index') }}" class="small-box-footer text-muted bg-light border-top">
                All Examinations <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

<!-- Main Dashboard Data Section -->
<div class="row">
    <!-- Recent Examinations Table -->
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
                <h3 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-procedures text-primary mr-2"></i> Recent Examinations
                </h3>
                <a href="{{ route('examinations.index') }}" class="btn btn-sm btn-link text-primary font-weight-bold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th>Exam ID</th>
                            <th>Patient</th>
                            <th>Procedure</th>
                            <th>Doctor</th>
                            <th>Date / Time</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentExaminations as $exam)
                            <tr>
                                <td>
                                    <a href="{{ route('examinations.show', $exam) }}" class="font-weight-bold text-dark">
                                        <code>{{ $exam->examination_id }}</code>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('patients.show', $exam->patient) }}" class="font-weight-semibold">
                                        {{ $exam->patient->name }}
                                    </a>
                                    <div class="small text-muted">{{ $exam->patient->patient_id }} &bull; {{ $exam->patient->gender }}, {{ $exam->patient->computed_age ?? 'N/A' }}y</div>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-truncate" style="max-width: 150px;" title="{{ $exam->procedure->name }}">
                                        {{ $exam->procedure->code }} - {{ Str::limit($exam->procedure->name, 18) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small font-weight-bold">{{ $exam->doctor->name }}</span>
                                </td>
                                <td>
                                    <span class="small font-weight-semibold">{{ $exam->examination_date->format('M d, Y') }}</span>
                                    <div class="small text-muted">{{ date('h:i A', strtotime($exam->examination_time)) }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-code {{ $exam->status_badge }}">
                                        {{ $exam->status }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('examinations.show', $exam) }}" class="btn btn-xs btn-outline-primary" title="View Examination">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($exam->report)
                                        <a href="{{ route('reports.show', $exam->report) }}" class="btn btn-xs btn-outline-success" title="View Report">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> No examinations recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pending Reports Queue & Procedure Distribution -->
    <div class="col-lg-5">
        <!-- Pending Reports Card -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
                <h3 class="card-title font-weight-bold mb-0 text-warning">
                    <i class="fas fa-clock mr-2"></i> Pending Reports Queue
                </h3>
                <span class="badge badge-warning font-weight-bold">{{ $pendingReports->count() }} Pending</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th>Patient / Exam</th>
                            <th>Procedure</th>
                            <th>Doctor</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingReports as $pExam)
                            <tr>
                                <td>
                                    <a href="{{ route('patients.show', $pExam->patient) }}" class="font-weight-bold text-dark">
                                        {{ $pExam->patient->name }}
                                    </a>
                                    <div class="small text-muted"><code>{{ $pExam->examination_id }}</code></div>
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $pExam->procedure->code }}</span>
                                </td>
                                <td>
                                    <span class="small">{{ $pExam->doctor->name }}</span>
                                </td>
                                <td class="text-right">
                                    @can('report.create')
                                        <a href="{{ route('reports.edit', $pExam) }}" class="btn btn-xs btn-warning font-weight-bold">
                                            <i class="fas fa-edit mr-1"></i> Write Report
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-check-circle text-success mr-1"></i> All completed examinations have finalized reports!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Procedures Distribution Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-2">
                <h3 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-chart-pie text-info mr-2"></i> Procedures Breakdown
                </h3>
            </div>
            <div class="card-body p-3">
                @foreach($topProcedures as $proc)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small font-weight-bold mb-1">
                            <span>{{ $proc->name }} ({{ $proc->code }})</span>
                            <span class="text-primary">{{ $proc->examinations_count }} exams</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            @php
                                $percent = $totalExaminationsCount > 0 ? ($proc->examinations_count / $totalExaminationsCount) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
