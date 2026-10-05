@extends('layouts.app')

@section('title', 'System Users')
@section('page_title', 'Clinical Workstation Users')
@section('page_subtitle', 'Manage authenticated medical personnel, operators, and administrators')

@section('breadcrumb')
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title font-weight-bold mb-0">
            <i class="fas fa-users-cog text-primary mr-2"></i> User Accounts
            <span class="badge badge-light border ml-2">{{ $users->total() }} Users</span>
        </h3>
        @can('user.create')
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus mr-1"></i> Add User
            </a>
        @endcan
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 text-nowrap">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email Address</th>
                    <th>Clinical Designation</th>
                    <th>Assigned Roles</th>
                    <th>Last Active</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="font-weight-bold text-dark">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white mr-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 13px; font-weight: bold;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    {{ $user->name }}
                                    @if($user->phone)
                                        <div class="small text-muted">{{ $user->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $user->email }}</code></td>
                        <td>{{ $user->designation ?? '—' }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge badge-primary px-2">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <span class="small">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never logged in' }}</span>
                        </td>
                        <td>
                            @if($user->status === 'Active')
                                <span class="badge badge-success px-2 py-1">Active</span>
                            @elseif($user->status === 'Suspended')
                                <span class="badge badge-danger px-2 py-1">Suspended</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1">Inactive</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                @can('user.edit')
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary" title="Edit User">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endcan
                                @can('user.delete')
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove user account?');">
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
                            No users registered.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white border-top py-2">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
