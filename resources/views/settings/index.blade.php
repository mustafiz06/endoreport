@extends('layouts.app')

@section('title', 'Organization Settings')
@section('page_title', 'Organization & Diagnostic Report Configuration')
@section('page_subtitle', 'Hospital profile, legal licensing numbers, report headers, and official branding')

@section('breadcrumb')
    <li class="breadcrumb-item active">Organization Settings</li>
@endsection

@section('content')
<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- 1. Organization Profile Card -->
        <div class="col-lg-6">
            <div class="card card-outline card-primary shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-hospital text-primary mr-2"></i> 1. Organization Profile
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Center / Hospital Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $setting->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="registration_number">Medical Registration Number</label>
                            <input type="text" name="registration_number" id="registration_number" class="form-control @error('registration_number') is-invalid @enderror" value="{{ old('registration_number', $setting->registration_number) }}" placeholder="e.g. MED-REG-2026">
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="license_number">Health Authority License No.</label>
                            <input type="text" name="license_number" id="license_number" class="form-control @error('license_number') is-invalid @enderror" value="{{ old('license_number', $setting->license_number) }}" placeholder="e.g. LIC-DGHS-88231">
                            @error('license_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="phone">Phone / Helpline</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $setting->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="email">Official Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $setting->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="website">Official Website URL</label>
                        <input type="text" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $setting->website) }}" placeholder="https://example.com">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="address">Full Facility Address</label>
                        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $setting->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Diagnostic Report Settings Card -->
        <div class="col-lg-6">
            <div class="card card-outline card-info shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-file-medical-alt text-info mr-2"></i> 2. Diagnostic Report Branding & Layout
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="report_header">Report Top Header Banner</label>
                        <input type="text" name="report_header" id="report_header" class="form-control @error('report_header') is-invalid @enderror" value="{{ old('report_header', $setting->report_header) }}" placeholder="e.g. APEX ADVANCED ENDOSCOPY & DIAGNOSTIC CENTER">
                        <small class="text-muted">Printed across the top of all clinical report sheets.</small>
                    </div>

                    <div class="form-group">
                        <label for="report_footer">Report Legal & Medical Disclaimer</label>
                        <textarea name="report_footer" id="report_footer" rows="2" class="form-control @error('report_footer') is-invalid @enderror">{{ old('report_footer', $setting->report_footer) }}</textarea>
                        <small class="text-muted">Printed at the bottom of diagnostic report exports.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="default_report_font">Default Report Font</label>
                            <select name="default_report_font" id="default_report_font" class="form-control" required>
                                @foreach(['Arial', 'Helvetica', 'Times New Roman', 'Roboto'] as $font)
                                    <option value="{{ $font }}" {{ old('default_report_font', $setting->default_report_font) === $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="default_report_format">Default Paper Format</label>
                            <select name="default_report_format" id="default_report_format" class="form-control" required>
                                <option value="A4" {{ old('default_report_format', $setting->default_report_format) === 'A4' ? 'selected' : '' }}>A4 (Standard)</option>
                                <option value="Letter" {{ old('default_report_format', $setting->default_report_format) === 'Letter' ? 'selected' : '' }}>Letter</option>
                            </select>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6 form-group">
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" class="custom-control-input" id="doctor_signature" name="doctor_signature" value="1" {{ old('doctor_signature', $setting->doctor_signature) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold" for="doctor_signature">
                                    Display Doctor Signature
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">If enabled, the performing doctor's signature file will render on report printouts.</small>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="stamp">Hospital Stamp Image</label>
                            <input type="file" name="stamp" id="stamp" class="form-control-file @error('stamp') is-invalid @enderror" accept="image/*">
                            <small class="text-muted d-block mt-1">PNG/JPG clinic stamp seal.</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Save Organization & Report Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
