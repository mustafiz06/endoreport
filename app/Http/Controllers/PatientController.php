<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\AuditLog;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Patient::query()->withCount('examinations');

        // Search
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Status Filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Gender Filter
        if ($gender = $request->input('gender')) {
            $query->where('gender', $gender);
        }

        // Blood Group Filter
        if ($bloodGroup = $request->input('blood_group')) {
            $query->where('blood_group', $bloodGroup);
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    public function create(): View
    {
        return view('patients.create');
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = Auth::id();

        // Calculate age if DOB provided
        if (!empty($validated['date_of_birth']) && empty($validated['age'])) {
            $validated['age'] = Carbon::parse($validated['date_of_birth'])->age;
        }

        $patient = Patient::create($validated);

        AuditLog::log(
            action: 'Created Patient',
            module: 'Patients',
            recordId: $patient->id,
            recordIdentifier: $patient->patient_id,
            newValues: $patient->toArray()
        );

        return redirect()->route('patients.show', $patient)
            ->with('success', "Patient record [{$patient->patient_id}] created successfully.");
    }

    public function show(Patient $patient): View
    {
        $patient->load([
            'examinations.procedure',
            'examinations.doctor',
            'examinations.referringDoctor',
            'examinations.report',
            'examinations.images',
            'reports.doctor',
            'reports.examination.procedure',
            'images.examination',
            'videos.examination',
            'creator',
        ]);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient): View
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validated();

        if (!empty($validated['date_of_birth'])) {
            $validated['age'] = Carbon::parse($validated['date_of_birth'])->age;
        }

        $oldValues = $patient->only(array_keys($validated));
        $patient->update($validated);

        AuditLog::log(
            action: 'Updated Patient',
            module: 'Patients',
            recordId: $patient->id,
            recordIdentifier: $patient->patient_id,
            oldValues: $oldValues,
            newValues: $validated
        );

        return redirect()->route('patients.show', $patient)
            ->with('success', "Patient [{$patient->patient_id}] information updated successfully.");
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        // Check medical history constraint
        if ($patient->hasMedicalHistory()) {
            return back()->with('error', "Medical Safety Violation: Patient [{$patient->patient_id}] has recorded examination history and cannot be deleted. You may change their status to 'Archived' or 'Inactive'.");
        }

        $identifier = $patient->patient_id;
        $id = $patient->id;
        $patient->delete();

        AuditLog::log(
            action: 'Deleted Patient',
            module: 'Patients',
            recordId: $id,
            recordIdentifier: $identifier
        );

        return redirect()->route('patients.index')
            ->with('success', "Patient record [{$identifier}] has been archived/removed.");
    }
}
