@extends('layouts.app')

@section('title', 'Procedures Catalog')
@section('page_title', 'Endoscopic Procedures Catalog')
@section('page_subtitle', 'Definitions, codes, standard durations, and template structures for diagnostic endoscopy')

@section('breadcrumb')
    <li class="breadcrumb-item active">Procedures</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title font-weight-bold mb-0">
            <i class="fas fa-procedures text-primary mr-2"></i> Clinical Procedures
            <span class="badge badge-light border ml-2">{{ $procedures->total() }} Procedures</span>
        </h3>
        @can('procedure.create')
            <a href="{{ route('procedures.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle mr-1"></i> Add Procedure
            </a>
        @endcan
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 text-nowrap">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Procedure Name</th>
                    <th>Estimated Duration</th>
                    <th>Template Defined</th>
                    <th>Examinations</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($procedures as $proc)
                    <tr>
                        <td>
                            <code class="font-weight-bold text-primary font-monospace">{{ $proc->code }}</code>
                        </td>
                        <td>
                            <span class="font-weight-bold text-dark">{{ $proc->name }}</span>
                            @if($proc->description)
                                <div class="small text-muted text-truncate" style="max-width: 320px;">{{ $proc->description }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-light border">
                                <i class="fas fa-clock mr-1 text-muted"></i> {{ $proc->default_duration }} mins
                            </span>
                        </td>
                        <td>
                            @if($proc->default_report_template)
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Yes</span>
                            @else
                                <span class="badge badge-light border text-muted">No</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info px-2 py-1">
                                {{ $proc->examinations_count }} Examinations
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $proc->status === 'Active' ? 'success' : 'secondary' }} px-2 py-1">
                                {{ $proc->status }}
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                @can('procedure.edit')
                                    <a href="{{ route('procedures.edit', $proc) }}" class="btn btn-sm btn-outline-secondary" title="Edit Procedure">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endcan
                                @can('procedure.delete')
                                    @if($proc->examinations_count == 0)
                                        <form action="{{ route('procedures.destroy', $proc) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove procedure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No procedures registered.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($procedures->hasPages())
        <div class="card-footer bg-white border-top py-2">
            {{ $procedures->links() }}
        </div>
    @endif
</div>
@endsection
