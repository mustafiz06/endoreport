<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Doctor;
use App\Models\Examination;
use App\Models\OrganizationSetting;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = Report::with(['patient', 'examination.procedure', 'doctor']);

        if ($filter = $request->input('filter')) {
            if ($filter === 'pending') {
                $query->where('status', 'Draft');
            } elseif ($filter === 'finalized') {
                $query->where('status', 'Finalized');
            }
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                       ->orWhere('patient_id', 'like', "%{$search}%");
                })->orWhereHas('examination', function ($eq) use ($search) {
                    $eq->where('examination_id', 'like', "%{$search}%");
                });
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('reports.index', compact('reports'));
    }

    public function show(Report $report, Request $request): View
    {
        $report->load([
            'patient',
            'examination.procedure',
            'examination.doctor',
            'examination.referringDoctor',
            'examination.instrument',
            'examination.images',
            'doctor',
            'finalizer',
        ]);

        $org = OrganizationSetting::current();
        $isPrint = $request->boolean('print');

        return view('reports.show', compact('report', 'org', 'isPrint'));
    }

    public function edit(Examination $examination): View
    {
        $report = $examination->report;
        $doctors = Doctor::active()->orderBy('name')->get();

        // If no report exists, prefill default template from procedure
        $defaultFindings = $report?->findings ?? $examination->procedure->default_report_template ?? '';
        $defaultImpression = $report?->impression ?? '';
        $defaultRecommendation = $report?->recommendation ?? '';

        return view('reports.edit', compact(
            'examination',
            'report',
            'doctors',
            'defaultFindings',
            'defaultImpression',
            'defaultRecommendation'
        ));
    }

    public function update(Request $request, Examination $examination): RedirectResponse
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'findings' => ['required', 'string'],
            'impression' => ['required', 'string'],
            'recommendation' => ['nullable', 'string'],
            'status' => ['required', 'in:Draft,Finalized'],
        ]);

        $isFinalizing = $validated['status'] === 'Finalized';

        if ($isFinalizing && !Auth::user()->can('report.finalize')) {
            return back()->with('error', 'Unauthorized: You do not have [report.finalize] permission to sign off and finalize clinical reports.');
        }

        $reportData = [
            'patient_id' => $examination->patient_id,
            'examination_id' => $examination->id,
            'doctor_id' => $validated['doctor_id'],
            'findings' => $validated['findings'],
            'impression' => $validated['impression'],
            'recommendation' => $validated['recommendation'],
            'status' => $validated['status'],
        ];

        if ($isFinalizing) {
            $reportData['finalized_at'] = now();
            $reportData['finalized_by'] = Auth::id();

            // Advance examination status to Finalized
            $examination->update(['status' => 'Finalized']);
        } elseif ($examination->status === 'Scheduled' || $examination->status === 'Captured') {
            $examination->update(['status' => 'Reporting']);
        }

        $report = Report::updateOrCreate(
            ['examination_id' => $examination->id],
            $reportData
        );

        AuditLog::log(
            action: $isFinalizing ? 'Finalized Report' : 'Updated Report Draft',
            module: 'Reports',
            recordId: $report->id,
            recordIdentifier: $examination->examination_id,
            newValues: $report->toArray()
        );

        return redirect()->route('reports.show', $report)
            ->with('success', $isFinalizing ? "Diagnostic report finalized and signed off." : "Report draft saved successfully.");
    }
}
