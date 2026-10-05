@extends('layouts.app')

@section('title', 'Roles & Permissions')
@section('page_title', 'Roles & Clinical Access Control')
@section('page_subtitle', 'Role-based access authorization rules and privilege policies')

@section('breadcrumb')
    <li class="breadcrumb-item active">Roles & Permissions</li>
@endsection

@section('content')
<div class="row">
    @foreach($roles as $role)
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100 shadow-sm border {{ $role->slug === 'super-admin' ? 'border-primary' : '' }}">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-shield-alt {{ $role->slug === 'super-admin' ? 'text-danger' : 'text-primary' }} mr-1"></i>
                        {{ $role->name }}
                    </h5>
                    <span class="badge badge-light border">{{ $role->users_count }} Users</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">{{ $role->description }}</p>

                    <div class="d-flex justify-content-between align-items-center border-top pt-2">
                        <span class="small font-weight-bold">
                            @if($role->slug === 'super-admin')
                                <span class="text-success"><i class="fas fa-infinity mr-1"></i> All Permissions (Root)</span>
                            @else
                                <span class="text-info"><i class="fas fa-key mr-1"></i> {{ $role->permissions_count }} Active Permissions</span>
                            @endif
                        </span>

                        @can('role.edit')
                            @if($role->slug !== 'super-admin')
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-xs btn-outline-primary font-weight-bold">
                                    <i class="fas fa-sliders-h mr-1"></i> Configure Matrix
                                </a>
                            @else
                                <span class="badge badge-secondary">Locked System Role</span>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
