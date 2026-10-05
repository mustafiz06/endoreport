<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Procedure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportTemplateController extends Controller
{
    public function index(): View
    {
        $procedures = Procedure::active()->orderBy('name')->get();
        return view('reports.templates', compact('procedures'));
    }

    public function update(Request $request, Procedure $procedure): RedirectResponse
    {
        $validated = $request->validate([
            'default_report_template' => ['nullable', 'string'],
        ]);

        $oldTemplate = $procedure->default_report_template;
        $procedure->update($validated);

        AuditLog::log(
            action: 'Updated Report Template',
            module: 'Procedures',
            recordId: $procedure->id,
            recordIdentifier: $procedure->code,
            oldValues: ['template' => $oldTemplate],
            newValues: ['template' => $validated['default_report_template']]
        );

        return back()->with('success', "Template for [{$procedure->code}] updated successfully.");
    }
}
