<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\ReferringDoctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferringDoctorController extends Controller
{
    public function index(): View
    {
        $referringDoctors = ReferringDoctor::withCount('examinations')->orderBy('name')->paginate(15);
        return view('referring-doctors.index', compact('referringDoctors'));
    }

    public function create(): View
    {
        return view('referring-doctors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'hospital_chamber' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $referringDoctor = ReferringDoctor::create($validated);

        AuditLog::log(
            action: 'Created Referring Doctor',
            module: 'Referring Doctors',
            recordId: $referringDoctor->id,
            recordIdentifier: $referringDoctor->name,
            newValues: $referringDoctor->toArray()
        );

        return redirect()->route('referring-doctors.index')
            ->with('success', "Referring Doctor [{$referringDoctor->name}] added successfully.");
    }

    public function edit(ReferringDoctor $referringDoctor): View
    {
        return view('referring-doctors.edit', compact('referringDoctor'));
    }

    public function update(Request $request, ReferringDoctor $referringDoctor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'hospital_chamber' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $oldValues = $referringDoctor->only(array_keys($validated));
        $referringDoctor->update($validated);

        AuditLog::log(
            action: 'Updated Referring Doctor',
            module: 'Referring Doctors',
            recordId: $referringDoctor->id,
            recordIdentifier: $referringDoctor->name,
            oldValues: $oldValues,
            newValues: $validated
        );

        return redirect()->route('referring-doctors.index')
            ->with('success', "Referring Doctor [{$referringDoctor->name}] updated successfully.");
    }

    public function destroy(ReferringDoctor $referringDoctor): RedirectResponse
    {
        if ($referringDoctor->examinations()->exists()) {
            return back()->with('error', "Medical Governance Violation: Referring Doctor [{$referringDoctor->name}] is linked to patient examinations. Set status to Inactive instead.");
        }

        $name = $referringDoctor->name;
        $id = $referringDoctor->id;
        $referringDoctor->delete();

        AuditLog::log(
            action: 'Deleted Referring Doctor',
            module: 'Referring Doctors',
            recordId: $id,
            recordIdentifier: $name
        );

        return redirect()->route('referring-doctors.index')
            ->with('success', "Referring Doctor [{$name}] removed.");
    }
}
