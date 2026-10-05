@extends('layouts.app')

@section('title', 'Report Templates')
@section('page_title', 'Endoscopy Report Findings Templates')
@section('page_subtitle', 'Standard procedural report templates and organ-by-organ documentation structures')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Templates</li>
@endsection

@section('content')
<div class="row">
    @foreach($procedures as $proc)
        <div class="col-lg-6 mb-3">
            <div class="card h-100 shadow-sm border">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <div>
                        <code class="font-weight-bold text-primary mr-1">{{ $proc->code }}</code>
                        <span class="font-weight-bold text-dark">{{ $proc->name }}</span>
                    </div>
                    <span class="badge badge-light border">Est. {{ $proc->default_duration }}m</span>
                </div>
                <form action="{{ route('report-templates.update', $proc) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body py-2">
                        <textarea name="default_report_template" rows="7" class="form-control font-monospace small" placeholder="Define default organ observations...">{{ $proc->default_report_template }}</textarea>
                    </div>
                    <div class="card-footer bg-light py-2 text-right">
                        @can('procedure.edit')
                            <button type="submit" class="btn btn-xs btn-primary font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Update Template
                            </button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
