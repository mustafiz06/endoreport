@extends('layouts.app')

@section('title', 'Configure Role - ' . $role->name)
@section('page_title', 'Configure Role Privileges: ' . $role->name)
@section('page_subtitle', 'Assign clinical, procedural, and administrative permissions for this role')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active">{{ $role->name }}</li>
@endsection

@section('content')
<form action="{{ route('roles.update', $role) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="form-group mb-0">
                <label for="description">Role Description</label>
                <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $role->description) }}">
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
            <h3 class="card-title font-weight-bold mb-0">
                <i class="fas fa-th-list text-primary mr-2"></i> Clinical & System Permission Matrix
            </h3>
            <div>
                <button type="button" class="btn btn-xs btn-outline-secondary" onclick="toggleAll(true)">Select All</button>
                <button type="button" class="btn btn-xs btn-outline-secondary ml-1" onclick="toggleAll(false)">Deselect All</button>
            </div>
        </div>

        <div class="card-body">
            @php
                $rolePermIds = $role->permissions->pluck('id')->toArray();
            @endphp

            <div class="row">
                @foreach($permissionsByModule as $module => $permissions)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 border bg-light">
                            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                <h6 class="font-weight-bold mb-0 text-dark">
                                    <i class="fas fa-layer-group text-info mr-1"></i> {{ $module }}
                                </h6>
                                <span class="badge badge-light border">{{ count($permissions) }} perms</span>
                            </div>
                            <div class="card-body p-2 bg-white">
                                @foreach($permissions as $perm)
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="custom-control-input perm-check" id="perm_{{ $perm->id }}" {{ in_array($perm->id, old('permissions', $rolePermIds)) ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-normal small" for="perm_{{ $perm->id }}">
                                            <code>{{ $perm->name }}</code>
                                            <div class="text-muted" style="font-size: 0.76rem;">{{ $perm->description }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card-footer bg-light text-right">
            <a href="{{ route('roles.index') }}" class="btn btn-default mr-1">Cancel</a>
            <button type="submit" class="btn btn-primary font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save Permission Matrix
            </button>
        </div>
    </div>
</form>

<script>
    function toggleAll(state) {
        document.querySelectorAll('.perm-check').forEach(function(el) {
            el.checked = state;
        });
    }
</script>
@endsection
