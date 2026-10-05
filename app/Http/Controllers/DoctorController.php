<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(): View
    {
        $doctors = Doctor::withCount('examinations')->orderBy('name')->paginate(15);
        return view('doctors.index', compact('doctors'));
    }

    public function create(): View
    {
        $users = User::whereDoesntHave('doctor')->where('status', 'Active')->get();
        return view('doctors.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'qualification' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:100', 'unique:doctors,registration_number'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'in:Active,Inactive'],
            'user_id' => ['nullable', 'exists:users,id'],
            'signature' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('signatures', 'public');
            $validated['signature'] = $path;
        }

        $doctor = Doctor::create($validated);

        AuditLog::log(
            action: 'Created Doctor',
            module: 'Doctors',
            recordId: $doctor->id,
            recordIdentifier: $doctor->name,
            newValues: $doctor->toArray()
        );

        return redirect()->route('doctors.index')
            ->with('success', "Doctor [{$doctor->name}] registered successfully.");
    }

    public function edit(Doctor $doctor): View
    {
        $users = User::where(function ($q) use ($doctor) {
            $q->whereDoesntHave('doctor')->orWhere('id', $doctor->user_id);
        })->where('status', 'Active')->get();

        return view('doctors.edit', compact('doctor', 'users'));
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'qualification' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:100', Rule::unique('doctors')->ignore($doctor->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'in:Active,Inactive'],
            'user_id' => ['nullable', 'exists:users,id'],
            'signature' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('signature')) {
            if ($doctor->signature && Storage::disk('public')->exists($doctor->signature)) {
                Storage::disk('public')->delete($doctor->signature);
            }
            $validated['signature'] = $request->file('signature')->store('signatures', 'public');
        }

        $oldValues = $doctor->only(array_keys($validated));
        $doctor->update($validated);

        AuditLog::log(
            action: 'Updated Doctor',
            module: 'Doctors',
            recordId: $doctor->id,
            recordIdentifier: $doctor->name,
            oldValues: $oldValues,
            newValues: $validated
        );

        return redirect()->route('doctors.index')
            ->with('success', "Doctor [{$doctor->name}] profile updated successfully.");
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        if ($doctor->examinations()->exists()) {
            return back()->with('error', "Medical Governance Violation: Doctor [{$doctor->name}] is linked to clinical examinations and cannot be removed. Set status to Inactive instead.");
        }

        $name = $doctor->name;
        $id = $doctor->id;
        $doctor->delete();

        AuditLog::log(
            action: 'Deleted Doctor',
            module: 'Doctors',
            recordId: $id,
            recordIdentifier: $name
        );

        return redirect()->route('doctors.index')
            ->with('success', "Doctor [{$name}] removed from directory.");
    }
}
