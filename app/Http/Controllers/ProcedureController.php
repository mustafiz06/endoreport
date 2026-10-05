<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Procedure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProcedureController extends Controller
{
    public function index(): View
    {
        $procedures = Procedure::withCount('examinations')->orderBy('name')->paginate(15);
        return view('procedures.index', compact('procedures'));
    }

    public function create(): View
    {
        return view('procedures.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:procedures,code'],
            'description' => ['nullable', 'string'],
            'default_duration' => ['required', 'integer', 'min:5', 'max:360'],
            'default_report_template' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $procedure = Procedure::create($validated);

        AuditLog::log(
            action: 'Created Procedure',
            module: 'Procedures',
            recordId: $procedure->id,
            recordIdentifier: $procedure->code,
            newValues: $procedure->toArray()
        );

        return redirect()->route('procedures.index')
            ->with('success', "Procedure [{$procedure->name}] added successfully.");
    }

    public function edit(Procedure $procedure): View
    {
        return view('procedures.edit', compact('procedure'));
    }

    public function update(Request $request, Procedure $procedure): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('procedures')->ignore($procedure->id)],
            'description' => ['nullable', 'string'],
            'default_duration' => ['required', 'integer', 'min:5', 'max:360'],
            'default_report_template' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $oldValues = $procedure->only(array_keys($validated));
        $procedure->update($validated);

        AuditLog::log(
            action: 'Updated Procedure',
            module: 'Procedures',
            recordId: $procedure->id,
            recordIdentifier: $procedure->code,
            oldValues: $oldValues,
            newValues: $validated
        );

        return redirect()->route('procedures.index')
            ->with('success', "Procedure [{$procedure->name}] updated successfully.");
    }

    public function destroy(Procedure $procedure): RedirectResponse
    {
        if ($procedure->examinations()->exists()) {
            return back()->with('error', "Medical Governance Violation: Procedure [{$procedure->name}] is linked to patient examination history. Set status to Inactive instead.");
        }

        $code = $procedure->code;
        $id = $procedure->id;
        $procedure->delete();

        AuditLog::log(
            action: 'Deleted Procedure',
            module: 'Procedures',
            recordId: $id,
            recordIdentifier: $code
        );

        return redirect()->route('procedures.index')
            ->with('success', "Procedure [{$code}] removed.");
    }
}
