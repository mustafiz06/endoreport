@extends('layouts.app')

@section('title', 'Edit Doctor - ' . $doctor->name)
@section('page_title', 'Edit Doctor Profile')
@section('page_subtitle', 'Update clinical credentials, contact info, and digital signature for ' . $doctor->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('doctors.index') }}">Doctors</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-user-md text-primary mr-2"></i> Edit Doctor Details
                </h3>
            </div>
            <form action="{{ route('doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Doctor Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $doctor->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="qualification">Qualifications / Degrees <span class="text-danger">*</span></label>
                            <input type="text" name="qualification" id="qualification" class="form-control @error('qualification') is-invalid @enderror" value="{{ old('qualification', $doctor->qualification) }}" required>
                            @error('qualification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="specialization">Clinical Specialization <span class="text-danger">*</span></label>
                            <input type="text" name="specialization" id="specialization" class="form-control @error('specialization') is-invalid @enderror" value="{{ old('specialization', $doctor->specialization) }}" required>
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="registration_number">Medical Council Registration No. <span class="text-danger">*</span></label>
                            <input type="text" name="registration_number" id="registration_number" class="form-control @error('registration_number') is-invalid @enderror" value="{{ old('registration_number', $doctor->registration_number) }}" required>
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="user_id">Linked User Account</label>
                            <select name="user_id" id="user_id" class="form-control select2">
                                <option value="">None (Independent record)</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $doctor->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $doctor->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $doctor->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6 form-group">
                            <label for="signature">Signature File</label>
                            @if($doctor->signature)
                                <div class="mb-2 p-2 border rounded bg-light" style="max-width: 200px;">
                                    <img src="{{ asset('storage/' . $doctor->signature) }}" alt="Signature" class="img-fluid">
                                </div>
                            @endif
                            <input type="file" name="signature" id="signature" class="form-control-file @error('signature') is-invalid @enderror" accept="image/*">
                            @error('signature')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="Active" {{ old('status', $doctor->status) === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status', $doctor->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <a href="{{ route('doctors.index') }}" class="btn btn-default mr-1">Cancel</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Update Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
