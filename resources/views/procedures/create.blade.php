@extends('layouts.app')

@section('title', 'Add Procedure')
@section('page_title', 'Create New Endoscopic Procedure')
@section('page_subtitle', 'Define procedural codes, estimated duration, and findings template')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('procedures.index') }}">Procedures</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-9 mx-auto">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-procedures text-primary mr-2"></i> Procedure Details Form
                </h3>
            </div>
            <form action="{{ route('procedures.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="name">Procedure Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Upper GI Endoscopy (Esophagogastroduodenoscopy)" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="code">Procedure Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control font-monospace @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="e.g. UGIE" required>
                            <small class="text-muted">Unique clinical shorthand (UGIE, COL, ERCP, BRON)</small>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="default_duration">Default Duration (Minutes) <span class="text-danger">*</span></label>
                            <input type="number" name="default_duration" id="default_duration" class="form-control @error('default_duration') is-invalid @enderror" value="{{ old('default_duration', 30) }}" min="5" max="360" required>
                            @error('default_duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Clinical Description / Preparation Guidelines</label>
                        <textarea name="description" id="description" rows="2" class="form-control @error('description') is-invalid @enderror" placeholder="Scope extent, patient preparation requirements...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="default_report_template">Default Diagnostic Report Template</label>
                        <textarea name="default_report_template" id="default_report_template" rows="7" class="form-control font-monospace small @error('default_report_template') is-invalid @enderror" placeholder="Standard organ-by-organ breakdown, e.g. Esophagus, Stomach, Duodenum...">{{ old('default_report_template') }}</textarea>
                        <small class="text-muted d-block mt-1">This template will pre-populate reports written for this procedure.</small>
                        @error('default_report_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <a href="{{ route('procedures.index') }}" class="btn btn-default mr-1">Cancel</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Save Procedure
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
