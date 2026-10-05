@extends('layouts.app')

@section('title', 'Referring Doctors')
@section('page_title', 'Referring Physicians & Clinics')
@section('page_subtitle', 'External medical practitioners, surgeons, and clinics referring patients for diagnostic investigations')

@section('breadcrumb')
    <li class="breadcrumb-item active">Referring Doctors</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title font-weight-bold mb-0">
            <i class="fas fa-user-tag text-info mr-2"></i> Referring Physicians Directory
            <span class="badge badge-light border ml-2">{{ $referringDoctors->total() }} Doctors</span>
        </h3>
        @can('referring_doctor.create')
            <a href="{{ route('referring-doctors.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle mr-1"></i> Add Referring Doctor
            </a>
        @endcan
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 text-nowrap">
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Qualifications</th>
                    <th>Specialization</th>
                    <th>Hospital / Chamber</th>
                    <th>Contact Phone</th>
                    <th>Patient Referrals</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referringDoctors as $doc)
                    <tr>
                        <td class="font-weight-bold text-dark">
                            <i class="fas fa-user-tie text-secondary mr-1"></i> {{ $doc->name }}
                        </td>
                        <td>{{ $doc->qualification ?? '—' }}</td>
                        <td>
                            @if($doc->specialization)
                                <span class="badge badge-light border">{{ $doc->specialization }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $doc->hospital_chamber ?? 'Private Chamber' }}</td>
                        <td>
                            <div><i class="fas fa-phone text-muted mr-1 small"></i> {{ $doc->phone ?? 'N/A' }}</div>
                            @if($doc->email)
                                <div class="small text-muted">{{ $doc->email }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info px-2 py-1">
                                {{ $doc->examinations_count }} Referrals
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $doc->status === 'Active' ? 'success' : 'secondary' }} px-2 py-1">
                                {{ $doc->status }}
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                @can('referring_doctor.edit')
                                    <a href="{{ route('referring-doctors.edit', $doc) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endcan
                                @can('referring_doctor.delete')
                                    @if($doc->examinations_count == 0)
                                        <form action="{{ route('referring-doctors.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this referring doctor?');">
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
                            No referring doctors registered in directory yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($referringDoctors->hasPages())
        <div class="card-footer bg-white border-top py-2">
            {{ $referringDoctors->links() }}
        </div>
    @endif
</div>
@endsection
