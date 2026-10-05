@extends('layouts.app')

@section('title', 'Endoscopy Instruments')
@section('page_title', 'Endoscopic Instruments & Hardware Inventory')
@section('page_subtitle', 'Endoscopes, video processors, light sources, and diagnostic equipment tracking')

@section('breadcrumb')
    <li class="breadcrumb-item active">Instruments</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title font-weight-bold mb-0">
            <i class="fas fa-microscope text-primary mr-2"></i> Medical Equipment Inventory
            <span class="badge badge-light border ml-2">{{ $instruments->total() }} Devices</span>
        </h3>
        @can('instrument.create')
            <a href="{{ route('instruments.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle mr-1"></i> Add Instrument
            </a>
        @endcan
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 text-nowrap">
            <thead>
                <tr>
                    <th>Device / Scope Name</th>
                    <th>Category</th>
                    <th>Manufacturer & Model</th>
                    <th>Serial Number</th>
                    <th>Assigned Suite</th>
                    <th>Examinations</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($instruments as $inst)
                    <tr>
                        <td class="font-weight-bold text-dark">
                            <i class="fas fa-video text-secondary mr-1"></i> {{ $inst->name }}
                        </td>
                        <td>
                            <span class="badge badge-light border">{{ $inst->device_type }}</span>
                        </td>
                        <td>
                            <div>{{ $inst->manufacturer ?? '—' }}</div>
                            @if($inst->model)
                                <div class="small text-muted">{{ $inst->model }}</div>
                            @endif
                        </td>
                        <td>
                            @if($inst->serial_number)
                                <code class="font-weight-semibold">{{ $inst->serial_number }}</code>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>{{ $inst->department ?? 'General Suite' }}</td>
                        <td>
                            <span class="badge badge-info px-2 py-1">
                                {{ $inst->examinations_count }} Exams
                            </span>
                        </td>
                        <td>
                            @if($inst->status === 'Active')
                                <span class="badge badge-success px-2 py-1">Active</span>
                            @elseif($inst->status === 'Maintenance')
                                <span class="badge badge-warning px-2 py-1">In Maintenance</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1">Inactive</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                @can('instrument.edit')
                                    <a href="{{ route('instruments.edit', $inst) }}" class="btn btn-sm btn-outline-secondary" title="Edit Device Details">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endcan
                                @can('instrument.delete')
                                    @if($inst->examinations_count == 0)
                                        <form action="{{ route('instruments.destroy', $inst) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove instrument?');">
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
                        <td colspan="8" class="text-center py-4 text-muted">
                            No instruments registered in inventory.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($instruments->hasPages())
        <div class="card-footer bg-white border-top py-2">
            {{ $instruments->links() }}
        </div>
    @endif
</div>
@endsection
