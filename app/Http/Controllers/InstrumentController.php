<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Instrument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InstrumentController extends Controller
{
    public function index(): View
    {
        $instruments = Instrument::withCount('examinations')->orderBy('name')->paginate(15);
        return view('instruments.index', compact('instruments'));
    }

    public function create(): View
    {
        return view('instruments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'device_type' => ['required', 'string', 'max:150'],
            'manufacturer' => ['nullable', 'string', 'max:150'],
            'model' => ['nullable', 'string', 'max:150'],
            'serial_number' => ['nullable', 'string', 'max:100', 'unique:instruments,serial_number'],
            'department' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:Active,Maintenance,Inactive'],
        ]);

        $instrument = Instrument::create($validated);

        AuditLog::log(
            action: 'Created Instrument',
            module: 'Instruments',
            recordId: $instrument->id,
            recordIdentifier: $instrument->name,
            newValues: $instrument->toArray()
        );

        return redirect()->route('instruments.index')
            ->with('success', "Instrument [{$instrument->name}] added successfully.");
    }

    public function edit(Instrument $instrument): View
    {
        return view('instruments.edit', compact('instrument'));
    }

    public function update(Request $request, Instrument $instrument): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'device_type' => ['required', 'string', 'max:150'],
            'manufacturer' => ['nullable', 'string', 'max:150'],
            'model' => ['nullable', 'string', 'max:150'],
            'serial_number' => ['nullable', 'string', 'max:100', Rule::unique('instruments')->ignore($instrument->id)],
            'department' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:Active,Maintenance,Inactive'],
        ]);

        $oldValues = $instrument->only(array_keys($validated));
        $instrument->update($validated);

        AuditLog::log(
            action: 'Updated Instrument',
            module: 'Instruments',
            recordId: $instrument->id,
            recordIdentifier: $instrument->name,
            oldValues: $oldValues,
            newValues: $validated
        );

        return redirect()->route('instruments.index')
            ->with('success', "Instrument [{$instrument->name}] details updated.");
    }

    public function destroy(Instrument $instrument): RedirectResponse
    {
        if ($instrument->examinations()->exists()) {
            return back()->with('error', "Medical Governance Violation: Instrument [{$instrument->name}] has recorded examination logs. Set status to Maintenance or Inactive instead.");
        }

        $name = $instrument->name;
        $id = $instrument->id;
        $instrument->delete();

        AuditLog::log(
            action: 'Deleted Instrument',
            module: 'Instruments',
            recordId: $id,
            recordIdentifier: $name
        );

        return redirect()->route('instruments.index')
            ->with('success', "Instrument [{$name}] removed from inventory.");
    }
}
