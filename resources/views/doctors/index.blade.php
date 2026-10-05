@extends('layouts.app')

@section('title', 'Endoscopists & Doctors')
@section('page_title', 'Endoscopists & Performing Doctors')
@section('page_subtitle', 'Certified clinical specialists and consultants authorized to perform endoscopic procedures')

@section('breadcrumb')
    <li class="breadcrumb-item active">Doctors</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title font-weight-bold mb-0">
            <i class="fas fa-user-md text-primary mr-2"></i> Clinical Specialist Directory
            <span class="badge badge-light border ml-2">{{ $doctors->total() }} Doctors</span>
        </h3>
        @can('doctor.create')
            <a href="{{ route('doctors.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle mr-1"></i> Add Doctor
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
                    <th>Registration No.</th>
                    <th>Contact Info</th>
                    <th>Exams Performed</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td class="font-weight-bold text-dark">
                            <i class="fas fa-stethoscope text-primary mr-1"></i> {{ $doctor->name }}
                        </td>
                        <td>{{ $doctor->qualification }}</td>
                        <td>
                            <span class="badge badge-light border">{{ $doctor->specialization }}</span>
                        </td>
                        <td><code>{{ $doctor->registration_number }}</code></td>
                        <td>
                            <div><i class="fas fa-phone text-muted mr-1 small"></i> {{ $doctor->phone ?? 'N/A' }}</div>
                            @if($doctor->email)
                                <div class="small text-muted">{{ $doctor->email }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info px-2 py-1">
                                {{ $doctor->examinations_count }} Examinations
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $doctor->status === 'Active' ? 'success' : 'secondary' }} px-2 py-1">
                                {{ $doctor->status }}
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                @can('doctor.edit')
                                    <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-sm btn-outline-secondary" title="Edit Doctor Profile">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endcan
                                @can('doctor.delete')
                                    @if($doctor->examinations_count == 0)
                                        <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this doctor?');">
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
                            No doctors registered in directory yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($doctors->hasPages())
        <div class="card-footer bg-white border-top py-2">
            {{ $doctors->links() }}
        </div>
    @endif
</div>
@endsection
