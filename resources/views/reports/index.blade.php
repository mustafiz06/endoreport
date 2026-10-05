@extends('layouts.app')

@section('title', 'Diagnostic Reports')
@section('page_title', 'Endoscopy Diagnostic Reports')
@section('page_subtitle', 'Clinical findings, diagnostic impressions, and verified endoscopy reports')

@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<!-- Filter Cards -->
<div class="card shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row align-items-center">
                <div class="col-md-5 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control" placeholder="Search by patient name, ID, or Exam ID (EX-...)" value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">
                            All Reports
                        </a>
                        <a href="{{ route('reports.index', ['filter' => 'pending']) }}" class="btn btn-sm btn-outline-warning {{ request('filter') === 'pending' ? 'active' : '' }}">
                            <i class="fas fa-edit mr-1"></i> Drafts
                        </a>
                        <a href="{{ route('reports.index', ['filter' => 'finalized']) }}" class="btn btn-sm btn-outline-success {{ request('filter') === 'finalized' ? 'active' : '' }}">
                            <i class="fas fa-check-circle mr-1"></i> Finalized
                        </a>
                    </div>
                </div>

                <div class="col-md-3 text-right">
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <i class="fas fa-filter mr-1"></i> Search
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm ml-1">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reports Table Card -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title font-weight-bold mb-0">
            <i class="fas fa-file-medical-alt text-primary mr-2"></i> Diagnostic Reports Index
            <span class="badge badge-light border ml-2">{{ $reports->total() }} Reports</span>
        </h3>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 text-nowrap">
            <thead>
                <tr>
                    <th>Exam ID</th>
                    <th>Patient Name & ID</th>
                    <th>Procedure</th>
                    <th>Reporting Endoscopist</th>
                    <th>Clinical Impression</th>
                    <th>Status</th>
                    <th>Finalized Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>
                            <a href="{{ route('examinations.show', $report->examination) }}" class="font-weight-bold">
                                <code>{{ $report->examination->examination_id }}</code>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('patients.show', $report->patient) }}" class="font-weight-semibold text-dark">
                                {{ $report->patient->name }}
                            </a>
                            <div class="small text-muted">{{ $report->patient->patient_id }}</div>
                        </td>
                        <td>
                            <span class="badge badge-light border">{{ $report->examination->procedure->code }}</span>
                            <span class="small font-weight-bold ml-1">{{ $report->examination->procedure->name }}</span>
                        </td>
                        <td>
                            <span class="small">{{ $report->doctor->name }}</span>
                        </td>
                        <td class="text-truncate" style="max-width: 250px;">
                            {{ Str::limit($report->impression ?? 'No impression written', 50) }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $report->status === 'Finalized' ? 'success' : 'warning' }} px-2 py-1">
                                {{ $report->status }}
                            </span>
                        </td>
                        <td>
                            <span class="small">{{ $report->finalized_at ? $report->finalized_at->format('M d, Y h:i A') : 'In Draft' }}</span>
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-outline-primary" title="View Full Report">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @can('report.print')
                                    <a href="{{ route('reports.show', ['report' => $report, 'print' => 1]) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Print Report">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @endcan
                                @can('report.edit')
                                    <a href="{{ route('reports.edit', $report->examination) }}" class="btn btn-sm btn-outline-warning" title="Edit Report">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-file-medical fa-2x mb-2 text-secondary d-block"></i>
                            No diagnostic reports match your query.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reports->hasPages())
        <div class="card-footer bg-white border-top py-2 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} reports</span>
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection
