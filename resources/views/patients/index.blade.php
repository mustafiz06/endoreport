@extends('layouts.app')

@section('title', 'Patient Registry')
@section('page_title', 'Patient Registry & Records')
@section('page_subtitle', 'Comprehensive patient index, demographic lookup, and examination records')

@section('breadcrumb')
    <li class="breadcrumb-item active">Patients</li>
@endsection

@section('content')
<!-- Filter & Search Panel -->
<div class="card shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('patients.index') }}">
            <div class="row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, ID (e.g. P-000001), phone, NID..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Archived" {{ request('status') === 'Archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="gender" class="form-control" onchange="this.form.submit()">
                        <option value="">All Genders</option>
                        <option value="Male" {{ request('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ request('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ request('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="blood_group" class="form-control" onchange="this.form.submit()">
                        <option value="">Blood Group</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                            <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary btn-sm ml-1" title="Reset Filters">
                        <i class="fas fa-undo"></i>
                    </a>
                    @can('patient.create')
                        <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm ml-1">
                            <i class="fas fa-user-plus mr-1"></i> Add
                        </a>
                    @endcan
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Patients List Card -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title font-weight-bold mb-0">
            <i class="fas fa-user-injured text-primary mr-2"></i> Patient Directory
            <span class="badge badge-light border ml-2">{{ $patients->total() }} Records</span>
        </h3>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 text-nowrap">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Gender & Age</th>
                    <th>Phone / Contact</th>
                    <th>Blood Group</th>
                    <th>Exams</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td>
                            <a href="{{ route('patients.show', $patient) }}" class="font-weight-bold text-dark">
                                <code class="text-primary font-weight-bold" style="font-size: 0.9rem;">{{ $patient->patient_id }}</code>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('patients.show', $patient) }}" class="font-weight-semibold text-dark">
                                {{ $patient->name }}
                            </a>
                            @if($patient->national_id)
                                <div class="small text-muted">NID: {{ $patient->national_id }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="font-weight-medium">{{ $patient->gender }}</span>,
                            <span class="text-muted">{{ $patient->computed_age ? $patient->computed_age . ' yrs' : 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="font-weight-medium"><i class="fas fa-phone-alt text-muted mr-1 small"></i> {{ $patient->phone }}</div>
                            @if($patient->email)
                                <div class="small text-muted">{{ $patient->email }}</div>
                            @endif
                        </td>
                        <td>
                            @if($patient->blood_group)
                                <span class="badge badge-danger px-2">{{ $patient->blood_group }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info px-2 py-1">
                                <i class="fas fa-procedures mr-1"></i> {{ $patient->examinations_count }}
                            </span>
                        </td>
                        <td>
                            @if($patient->status === 'Active')
                                <span class="badge badge-success px-2 py-1">Active</span>
                            @elseif($patient->status === 'Inactive')
                                <span class="badge badge-secondary px-2 py-1">Inactive</span>
                            @else
                                <span class="badge badge-warning px-2 py-1">Archived</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary" title="View Profile">
                                    <i class="fas fa-id-badge"></i>
                                </a>
                                @can('patient.edit')
                                    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-secondary" title="Edit Demographics">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('examination.create')
                                    <a href="{{ route('examinations.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-outline-success" title="New Examination">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                @endcan
                                <a href="{{ route('patients.show', ['patient' => $patient, 'tab' => 'history']) }}" class="btn btn-sm btn-outline-dark" title="Patient History">
                                    <i class="fas fa-history"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-user-slash fa-2x mb-2 d-block text-secondary"></i>
                            No patient records match the specified filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())
        <div class="card-footer bg-white border-top py-2 d-flex justify-content-between align-items-center">
            <span class="small text-muted">
                Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }} patients
            </span>
            {{ $patients->links() }}
        </div>
    @endif
</div>
@endsection
