@extends('layouts.app')

@section('title', 'Report ' . $report->examination->examination_id)
@section('page_title', 'Endoscopy Diagnostic Report')
@section('page_subtitle', 'Verified electronic medical report for ' . $report->patient->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item"><a href="{{ route('examinations.show', $report->examination) }}">{{ $report->examination->examination_id }}</a></li>
    <li class="breadcrumb-item active">Report</li>
@endsection

@push('styles')
<style>
    @media print {
        body {
            background-color: #ffffff !important;
            font-size: 11pt !important;
            color: #000000 !important;
        }
        .main-header, .main-sidebar, .main-footer, .content-header, .no-print, .btn, .breadcrumb {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }
        .report-paper {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            max-width: 100% !important;
            margin: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Action Buttons (No Print) -->
<div class="row mb-3 no-print">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('examinations.show', $report->examination) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Examination
            </a>
            <span class="badge badge-{{ $report->status === 'Finalized' ? 'success' : 'warning' }} px-3 py-2 ml-2" style="font-size: 0.9rem;">
                <i class="fas fa-{{ $report->status === 'Finalized' ? 'check-circle' : 'edit' }} mr-1"></i> {{ $report->status }} Report
            </span>
        </div>
        <div>
            @can('report.edit')
                <a href="{{ route('reports.edit', $report->examination) }}" class="btn btn-sm btn-outline-warning mr-1">
                    <i class="fas fa-edit mr-1"></i> Edit Report Content
                </a>
            @endcan
            <button type="button" class="btn btn-sm btn-primary font-weight-bold" onclick="window.print();">
                <i class="fas fa-print mr-1"></i> Print Diagnostic Report
            </button>
        </div>
    </div>
</div>

<!-- Formal Medical Report Sheet Paper -->
<div class="report-paper bg-white p-4 p-md-5 mb-5">
    <!-- 1. Header Banner & Organization Info -->
    <div class="report-header-banner border-bottom pb-3 mb-4">
        <div class="row align-items-center">
            <div class="col-8">
                <h3 class="font-weight-bold text-dark text-uppercase mb-1" style="letter-spacing: 0.5px;">
                    {{ $org->name ?? 'Apex Endoscopy & Diagnostic Center' }}
                </h3>
                @if($org->report_header)
                    <div class="font-weight-semibold text-secondary small mb-1">{{ $org->report_header }}</div>
                @endif
                <div class="text-muted small">
                    <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $org->address ?? 'Medical Plaza, Suite 400, New York' }}
                </div>
                <div class="text-muted small">
                    <i class="fas fa-phone mr-1"></i> {{ $org->phone ?? '+1 (555) 000-0000' }} &bull;
                    <i class="fas fa-envelope mr-1"></i> {{ $org->email ?? 'info@apex-endoscopy.com' }}
                </div>
            </div>
            <div class="col-4 text-right">
                <div class="d-inline-block text-right">
                    <div class="border rounded p-2 bg-light text-center">
                        <small class="text-muted font-weight-bold d-block">REGISTRATION</small>
                        <code class="small text-dark">{{ $org->registration_number ?? 'MED-REG-2026' }}</code>
                        <small class="text-muted font-weight-bold d-block mt-1">LICENSE NO</small>
                        <code class="small text-dark">{{ $org->license_number ?? 'LIC-DGHS-88231' }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Patient & Examination Details Matrix -->
    <div class="p-3 bg-light rounded border mb-4">
        <div class="row">
            <div class="col-md-6 border-right">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width: 35%;">Patient Name:</th>
                        <td class="font-weight-bold text-dark">{{ $report->patient->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Patient ID:</th>
                        <td><code class="font-weight-bold text-primary">{{ $report->patient->patient_id }}</code></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Age / Gender:</th>
                        <td>{{ $report->patient->computed_age ?? 'N/A' }} Years / {{ $report->patient->gender }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Contact Phone:</th>
                        <td>{{ $report->patient->phone }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width: 40%;">Exam Ref ID:</th>
                        <td><code class="font-weight-bold text-dark">{{ $report->examination->examination_id }}</code></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Date of Exam:</th>
                        <td>{{ $report->examination->examination_date->format('F d, Y') }} at {{ date('h:i A', strtotime($report->examination->examination_time)) }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Referring Doctor:</th>
                        <td>{{ $report->examination->referringDoctor?->name ?? 'Self-Referred / None' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Equipment Used:</th>
                        <td>{{ $report->examination->instrument?->name ?? 'Standard Video Endoscope' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. Procedure Title & Clinical Indication -->
    <div class="text-center my-4 py-2 border-top border-bottom bg-light">
        <h4 class="font-weight-bold text-dark text-uppercase mb-1">
            {{ $report->examination->procedure->name }}
        </h4>
        <span class="text-muted small">Procedure Code: <strong>{{ $report->examination->procedure->code }}</strong></span>
    </div>

    <div class="mb-4">
        <h6 class="font-weight-bold text-uppercase text-secondary border-bottom pb-1">
            <i class="fas fa-stethoscope mr-1"></i> Clinical Indication
        </h6>
        <p class="text-dark mb-0 font-weight-medium" style="white-space: pre-line;">{{ $report->examination->indication }}</p>
    </div>

    <!-- 4. Findings -->
    <div class="mb-4">
        <h6 class="font-weight-bold text-uppercase text-secondary border-bottom pb-1">
            <i class="fas fa-microscope mr-1"></i> Endoscopic Findings
        </h6>
        <div class="text-dark p-2" style="white-space: pre-line; line-height: 1.6; font-size: 0.95rem;">
            {{ $report->findings }}
        </div>
    </div>

    <!-- 5. Impression (Clinical Diagnosis) -->
    <div class="mb-4 p-3 rounded" style="background-color: #f0fdf4; border-left: 4px solid #16a34a;">
        <h6 class="font-weight-bold text-uppercase text-success mb-2">
            <i class="fas fa-check-circle mr-1"></i> Impression / Diagnosis
        </h6>
        <div class="font-weight-bold text-dark" style="white-space: pre-line; line-height: 1.6; font-size: 1.05rem;">
            {{ $report->impression }}
        </div>
    </div>

    <!-- 6. Recommendations -->
    @if($report->recommendation)
        <div class="mb-4">
            <h6 class="font-weight-bold text-uppercase text-secondary border-bottom pb-1">
                <i class="fas fa-clipboard-list mr-1"></i> Recommendations & Advice
            </h6>
            <div class="text-dark p-2" style="white-space: pre-line; line-height: 1.6;">
                {{ $report->recommendation }}
            </div>
        </div>
    @endif

    <!-- 7. Sign-off, Doctor Signature & Hospital Stamp -->
    <div class="mt-5 pt-4 border-top">
        <div class="row align-items-end">
            <div class="col-6">
                @if($org->stamp)
                    <div class="p-2 border d-inline-block text-center rounded bg-light" style="width: 140px; height: 70px;">
                        <small class="text-muted d-block font-weight-bold">OFFICIAL STAMP</small>
                        <i class="fas fa-certificate text-danger fa-2x mt-1"></i>
                    </div>
                @else
                    <div class="p-2 border d-inline-block text-center rounded text-muted small" style="width: 130px; height: 60px; line-height: 40px; border-style: dashed !important;">
                        Official Clinic Stamp
                    </div>
                @endif
                <div class="small text-muted mt-2">
                    Report Generated: {{ now()->format('M d, Y h:i A') }}
                </div>
            </div>

            <div class="col-6 text-right">
                @if($org->doctor_signature && $report->doctor->signature)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $report->doctor->signature) }}" alt="Signature" style="max-height: 55px;">
                    </div>
                @else
                    <div class="mb-3 text-muted font-italic small" style="height: 40px; line-height: 40px;">
                        [ Electronically Verified Signature ]
                    </div>
                @endif
                <div class="font-weight-bold text-dark">{{ $report->doctor->name }}</div>
                <div class="small text-muted">{{ $report->doctor->qualification }}</div>
                <div class="small text-muted">{{ $report->doctor->specialization }}</div>
                <div class="small text-muted">Reg No: {{ $report->doctor->registration_number }}</div>
            </div>
        </div>
    </div>

    <!-- 8. Footer Legal Note -->
    <div class="mt-4 pt-3 border-top text-center text-muted small">
        <p class="mb-0">
            {{ $org->report_footer ?? 'This electronic diagnostic report is verified by authorized medical practitioners.' }}
        </p>
    </div>
</div>
@endsection

@push('scripts')
@if($isPrint)
<script>
    window.addEventListener('load', function() {
        window.print();
    });
</script>
@endif
@endpush
