@extends('layouts.app')

@section('title', 'User Profile & Security')
@section('page_title', 'User Profile & Security Settings')
@section('page_subtitle', 'Manage personal credentials and password security')

@section('breadcrumb')
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-5">
        <!-- User Info Card -->
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-body box-profile text-center py-4">
                <div class="rounded-circle bg-primary text-white mx-auto d-flex align-items-center justify-content-center mb-3 shadow" style="width: 80px; height: 80px; font-size: 32px; font-weight: bold;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h4 class="profile-username font-weight-bold mb-1">{{ Auth::user()->name }}</h4>
                <p class="text-muted mb-2">{{ Auth::user()->designation ?? 'Clinical Practitioner' }}</p>
                <div>
                    @foreach(Auth::user()->roles as $role)
                        <span class="badge badge-primary px-2 py-1"><i class="fas fa-shield-alt mr-1"></i> {{ $role->name }}</span>
                    @endforeach
                    <span class="badge badge-{{ Auth::user()->status === 'Active' ? 'success' : 'danger' }} px-2 py-1">
                        {{ Auth::user()->status }}
                    </span>
                </div>

                <ul class="list-group list-group-unbordered text-left mt-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="fas fa-envelope mr-2"></i> Email Address</span>
                        <span class="font-weight-bold">{{ Auth::user()->email }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="fas fa-phone mr-2"></i> Contact Phone</span>
                        <span>{{ Auth::user()->phone ?? 'Not specified' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="fas fa-clock mr-2"></i> Last Login</span>
                        <span class="small">{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('M d, Y h:i A') : 'First session' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="fas fa-network-wired mr-2"></i> Last Login IP</span>
                        <code>{{ Auth::user()->last_login_ip ?? '127.0.0.1' }}</code>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <!-- Change Password Card -->
        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-key mr-2 text-warning"></i> Change Password
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password">Current Password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required placeholder="Enter existing password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" required placeholder="Minimum 8 characters">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required placeholder="Repeat new password">
                    </div>

                    <div class="alert alert-light border small text-muted">
                        <i class="fas fa-info-circle mr-1 text-info"></i> Password must contain at least 8 characters. For enhanced security, include uppercase letters, numbers, and symbols.
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-warning font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Contact Details -->
        <div class="card card-outline card-info shadow-sm mt-3">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-id-card mr-2 text-info"></i> Update Contact Information
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', Auth::user()->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="designation">Clinical Designation / Title</label>
                        <input type="text" name="designation" id="designation" class="form-control @error('designation') is-invalid @enderror" value="{{ old('designation', Auth::user()->designation) }}">
                        @error('designation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-info font-weight-bold">
                            <i class="fas fa-check mr-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
