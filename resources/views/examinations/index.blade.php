@extends('layouts.app')

@section('title', 'Examinations Management')
@section('page_title', 'Endoscopy Examinations Management')
@section('page_subtitle', 'Schedule, live procedure tracking, and diagnostic examination logs')

@section('breadcrumb')
    <li class="breadcrumb-item active">Examinations</li>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="card shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('examinations.index') }}">
            <div class="row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control" placeholder="Exam ID (EX-2026-...) or Patient" value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        @foreach(['Scheduled', 'In Progress', 'Captured', 'Reporting', 'Reviewed', 'Finalized', 'Cancelled'] as $st)
                            <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="procedure_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Procedures</option>
                        @foreach($procedures as $proc)
                            <option value="{{ $proc->id }}" {{ request('procedure_id') == $proc->id ? 'selected' : '' }}>{{ $proc->code }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="doctor_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doc)
                            <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>{{ $doc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 text-right">
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('examinations.index') }}" class="btn btn-outline-secondary btn-sm ml-1" title="Reset">
                        <i class="fas fa-undo"></i>
                    </a>
                    @can('examination.create')
                        <a href="{{ route('examinations.create') }}" class="btn btn-primary btn-sm ml-1 font-weight-bold">
                            <i class="fas fa-plus-circle mr-1"></i> New Exam
                        </a>
                    @endcan
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Examinations Table Card -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title font-weight-bold mb-0">
            <i class="fas fa-notes-medical text-primary mr-2"></i> Clinical Examinations Log
            <span class="badge badge-light border ml-2">{{ $examinations->total() }} Records</span>
        </h3>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 text-nowrap">
            <thead>
                <tr>
                    <th>Exam ID</th>
                    <th>Patient Name & ID</th>
                    <th>Procedure</th>
                    <th>Performing Doctor</th>
                    <th>Referring Doctor</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Report</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($examinations as $exam)
                    <tr>
                        <td>
                            <a href="{{ route('examinations.show', $exam) }}" class="font-weight-bold text-dark">
                                <code class="text-primary font-weight-bold" style="font-size: 0.9rem;">{{ $exam->examination_id }}</code>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('patients.show', $exam->patient) }}" class="font-weight-bold text-dark">
                                {{ $exam->patient->name }}
                            </a>
                            <div class="small text-muted">{{ $exam->patient->patient_id }} &bull; {{ $exam->patient->gender }}, {{ $exam->patient->computed_age ?? 'N/A' }}y</div>
                        </td>
                        <td>
                            <span class="font-weight-semibold">{{ $exam->procedure->name }}</span>
                            <span class="badge badge-light border ml-1">{{ $exam->procedure->code }}</span>
                        </td>
                        <td>
                            <span class="small font-weight-medium">{{ $exam->doctor->name }}</span>
                        </td>
                        <td>
                            <span class="small text-muted">{{ $exam->referringDoctor?->name ?? 'Self-Referred' }}</span>
                        </td>
                        <td>
                            <div class="small font-weight-bold">{{ $exam->examination_date->format('M d, Y') }}</div>
                            <div class="small text-muted">{{ date('h:i A', strtotime($exam->examination_time)) }}</div>
                        </td>
                        <td>
                            <span class="badge badge-code {{ $exam->status_badge }}">
                                {{ $exam->status }}
                            </span>
                        </td>
                        <td>
                            @if($exam->report)
                                <span class="badge badge-{{ $exam->report->status === 'Finalized' ? 'success' : 'warning' }}">
                                    {{ $exam->report->status }}
                                </span>
                            @else
                                <span class="badge badge-light border text-muted">None</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                <a href="{{ route('examinations.show', $exam) }}" class="btn btn-sm btn-outline-primary" title="View Full Examination">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('examination.edit')
                                    <a href="{{ route('examinations.edit', $exam) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('report.create')
                                    <a href="{{ route('reports.edit', $exam) }}" class="btn btn-sm btn-outline-warning" title="Report">
                                        <i class="fas fa-file-medical"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fas fa-procedures fa-2x mb-2 d-block text-secondary"></i>
                            No examinations match the selected filter criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($examinations->hasPages())
        <div class="card-footer bg-white border-top py-2 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Showing {{ $examinations->firstItem() }} to {{ $examinations->lastItem() }} of {{ $examinations->total() }} examinations</span>
            {{ $examinations->links() }}
        </div>
    @endif
</div>
@endsection
