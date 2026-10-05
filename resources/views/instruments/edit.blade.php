@extends('layouts.app')

@section('title', 'Edit Instrument - ' . $instrument->name)
@section('page_title', 'Edit Equipment Information')
@section('page_subtitle', 'Update hardware status, suite assignment, or maintenance condition')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('instruments.index') }}">Instruments</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-microscope text-primary mr-2"></i> Edit Instrument: {{ $instrument->name }}
                </h3>
            </div>
            <form action="{{ route('instruments.update', $instrument) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Instrument / Device Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $instrument->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="device_type">Device Type / Category <span class="text-danger">*</span></label>
                            <input type="text" name="device_type" id="device_type" class="form-control @error('device_type') is-invalid @enderror" value="{{ old('device_type', $instrument->device_type) }}" required>
                            @error('device_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="department">Assigned Suite / Department</label>
                            <input type="text" name="department" id="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department', $instrument->department) }}">
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="manufacturer">Manufacturer</label>
                            <input type="text" name="manufacturer" id="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror" value="{{ old('manufacturer', $instrument->manufacturer) }}">
                            @error('manufacturer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="model">Model Number</label>
                            <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model', $instrument->model) }}">
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="serial_number">Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number" class="form-control font-monospace @error('serial_number') is-invalid @enderror" value="{{ old('serial_number', $instrument->serial_number) }}">
                            @error('serial_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="status">Operational Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="Active" {{ old('status', $instrument->status) === 'Active' ? 'selected' : '' }}>Active (Available for procedures)</option>
                            <option value="Maintenance" {{ old('status', $instrument->status) === 'Maintenance' ? 'selected' : '' }}>In Maintenance (Sterilization / Repair)</option>
                            <option value="Inactive" {{ old('status', $instrument->status) === 'Inactive' ? 'selected' : '' }}>Inactive / Decommissioned</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <a href="{{ route('instruments.index') }}" class="btn btn-default mr-1">Cancel</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Update Device
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
