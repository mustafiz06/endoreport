@extends('layouts.app')

@section('title', 'Clinical Audit Log')
@section('page_title', 'Clinical & System Audit Trail')
@section('page_subtitle', 'Immutable compliance log tracking patient record modifications, examination sign-offs, and security events')

@section('breadcrumb')
    <li class="breadcrumb-item active">Audit Log</li>
@endsection

@section('content')
<!-- Filter Panel -->
<div class="card shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('audit.index') }}">
            <div class="row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control" placeholder="Search identifier, user, IP..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="module" class="form-control" onchange="this.form.submit()">
                        <option value="">All Modules</option>
                        @foreach($modules as $mod)
                            <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="user_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Clinical Users</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 text-right">
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('audit.index') }}" class="btn btn-outline-secondary btn-sm ml-1">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Audit Log Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title font-weight-bold mb-0">
            <i class="fas fa-clipboard-list text-warning mr-2"></i> Audit Records
            <span class="badge badge-light border ml-2">{{ $logs->total() }} Log Entries</span>
        </h3>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 text-nowrap">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Module</th>
                    <th>Record Ref</th>
                    <th>IP Address</th>
                    <th class="text-right">Audit Payload</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="small font-weight-semibold">
                            <div>{{ $log->created_at->format('M d, Y') }}</div>
                            <span class="text-muted">{{ $log->created_at->format('h:i:s A') }}</span>
                        </td>
                        <td>
                            <span class="font-weight-bold text-dark">{{ $log->user_name ?? ($log->user?->name ?? 'System') }}</span>
                        </td>
                        <td>
                            <span class="badge badge-light border font-weight-semibold">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-info px-2 py-1">{{ $log->module }}</span>
                        </td>
                        <td>
                            @if($log->record_identifier)
                                <code>{{ $log->record_identifier }}</code>
                            @elseif($log->record_id)
                                <code>ID #{{ $log->record_id }}</code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted font-monospace">{{ $log->ip_address ?? '127.0.0.1' }}</small>
                        </td>
                        <td class="text-right">
                            @if($log->old_values || $log->new_values)
                                <button type="button" class="btn btn-xs btn-outline-info" data-toggle="modal" data-target="#auditModal-{{ $log->id }}">
                                    <i class="fas fa-code mr-1"></i> View Diff
                                </button>

                                <!-- Audit Detail Modal -->
                                <div class="modal fade text-left" id="auditModal-{{ $log->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header py-2 bg-light">
                                                <h5 class="modal-title font-weight-bold small text-uppercase">
                                                    <i class="fas fa-search-plus mr-1"></i> Audit Event #{{ $log->id }} &bull; {{ $log->action }} ({{ $log->module }})
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body py-2">
                                                <div class="small text-muted mb-2">
                                                    User Agent: <code>{{ $log->user_agent ?? 'Internal System Service' }}</code>
                                                </div>
                                                <div class="row">
                                                    @if($log->old_values)
                                                        <div class="col-md-6">
                                                            <strong class="text-danger small text-uppercase d-block mb-1">Previous Values:</strong>
                                                            <pre class="bg-light p-2 rounded border small font-monospace" style="max-height: 250px; overflow-y: auto;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                        </div>
                                                    @endif
                                                    @if($log->new_values)
                                                        <div class="col-md-{{ $log->old_values ? '6' : '12' }}">
                                                            <strong class="text-success small text-uppercase d-block mb-1">New / Current Values:</strong>
                                                            <pre class="bg-light p-2 rounded border small font-monospace" style="max-height: 250px; overflow-y: auto;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer py-1">
                                                <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small">No payload</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-clipboard-check fa-2x mb-2 text-secondary d-block"></i>
                            No audit log events match the query.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="card-footer bg-white border-top py-2 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} audit events</span>
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
