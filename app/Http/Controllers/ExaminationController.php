<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExaminationRequest;
use App\Http\Requests\UpdateExaminationRequest;
use App\Models\AuditLog;
use App\Models\Doctor;
use App\Models\Examination;
use App\Models\Instrument;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\ReferringDoctor;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExaminationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Examination::with(['patient', 'procedure', 'doctor', 'referringDoctor', 'report']);

        // Search by examination ID or patient details
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('examination_id', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('patient_id', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by Doctor
        if ($doctorId = $request->input('doctor_id')) {
            $query->where('doctor_id', $doctorId);
        }

        // Filter by Procedure
        if ($procedureId = $request->input('procedure_id')) {
            $query->where('procedure_id', $procedureId);
        }

        // Filter by Date
        if ($date = $request->input('date')) {
            $query->whereDate('examination_date', $date);
        }

        $examinations = $query->orderBy('examination_date', 'desc')
            ->orderBy('examination_time', 'desc')
            ->paginate(15)
            ->withQueryString();

        $doctors = Doctor::active()->orderBy('name')->get();
        $procedures = Procedure::active()->orderBy('name')->get();

        return view('examinations.index', compact('examinations', 'doctors', 'procedures'));
    }

    public function create(Request $request): View
    {
        $selectedPatient = null;
        if ($patientId = $request->input('patient_id')) {
            $selectedPatient = Patient::find($patientId);
        }

        $patients = Patient::active()->orderBy('name')->get();
        $doctors = Doctor::active()->orderBy('name')->get();
        $referringDoctors = ReferringDoctor::active()->orderBy('name')->get();
        $procedures = Procedure::active()->orderBy('name')->get();
        $instruments = Instrument::where('status', 'Active')->orderBy('name')->get();

        return view('examinations.create', compact(
            'selectedPatient',
            'patients',
            'doctors',
            'referringDoctors',
            'procedures',
            'instruments'
        ));
    }

    public function store(StoreExaminationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = Auth::id();

        $examination = DB::transaction(function () use ($validated) {
            $exam = Examination::create($validated);

            // If procedure has template and examination is not cancelled, prepare draft report
            $procedure = Procedure::find($validated['procedure_id']);
            if ($procedure && !empty($procedure->default_report_template)) {
                Report::create([
                    'patient_id' => $validated['patient_id'],
                    'examination_id' => $exam->id,
                    'doctor_id' => $validated['doctor_id'],
                    'report_template_id' => $procedure->id,
                    'findings' => $procedure->default_report_template,
                    'impression' => 'Pending examination findings review.',
                    'status' => 'Draft',
                ]);
            }

            return $exam;
        });

        AuditLog::log(
            action: 'Created Examination',
            module: 'Examinations',
            recordId: $examination->id,
            recordIdentifier: $examination->examination_id,
            newValues: $examination->toArray()
        );

        return redirect()->route('examinations.show', $examination)
            ->with('success', "Examination [{$examination->examination_id}] scheduled successfully.");
    }

    public function show(Examination $examination): View
    {
        $examination->load([
            'patient.examinations',
            'procedure',
            'doctor',
            'referringDoctor',
            'instrument',
            'report.doctor',
            'images',
            'videos',
            'creator',
        ]);

        return view('examinations.show', compact('examination'));
    }

    public function edit(Examination $examination): View|RedirectResponse
    {
        if ($examination->isFinalized() && !Auth::user()->can('examination.finalize')) {
            return back()->with('error', 'Cannot edit a finalized examination without clinical authorization.');
        }

        $doctors = Doctor::active()->orderBy('name')->get();
        $referringDoctors = ReferringDoctor::active()->orderBy('name')->get();
        $procedures = Procedure::active()->orderBy('name')->get();
        $instruments = Instrument::whereIn('status', ['Active', 'Maintenance'])->orderBy('name')->get();

        return view('examinations.edit', compact(
            'examination',
            'doctors',
            'referringDoctors',
            'procedures',
            'instruments'
        ));
    }

    public function update(UpdateExaminationRequest $request, Examination $examination): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['status'] === 'Finalized' && !Auth::user()->can('examination.finalize')) {
            return back()->with('error', 'Unauthorized: You do not possess [examination.finalize] permission to finalize this examination.');
        }

        $oldValues = $examination->only(array_keys($validated));
        $examination->update($validated);

        AuditLog::log(
            action: 'Updated Examination',
            module: 'Examinations',
            recordId: $examination->id,
            recordIdentifier: $examination->examination_id,
            oldValues: $oldValues,
            newValues: $validated
        );

        return redirect()->route('examinations.show', $examination)
            ->with('success', "Examination [{$examination->examination_id}] updated successfully.");
    }

    public function updateStatus(Request $request, Examination $examination): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:Scheduled,In Progress,Captured,Reporting,Reviewed,Finalized,Cancelled'],
        ]);

        $newStatus = $request->input('status');

        if ($newStatus === 'Finalized' && !Auth::user()->can('examination.finalize')) {
            return back()->with('error', 'Unauthorized: You do not have permission to finalize clinical examinations.');
        }

        $oldStatus = $examination->status;
        $examination->update(['status' => $newStatus]);

        AuditLog::log(
            action: "Changed Status ({$oldStatus} -> {$newStatus})",
            module: 'Examinations',
            recordId: $examination->id,
            recordIdentifier: $examination->examination_id,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => $newStatus]
        );

        return back()->with('success', "Examination status updated to [{$newStatus}].");
    }

    public function destroy(Examination $examination): RedirectResponse
    {
        if ($examination->isFinalized() || $examination->report?->isFinalized()) {
            return back()->with('error', "Medical Governance Violation: Finalized examination [{$examination->examination_id}] cannot be removed.");
        }

        $code = $examination->examination_id;
        $id = $examination->id;
        $examination->delete();

        AuditLog::log(
            action: 'Deleted Examination',
            module: 'Examinations',
            recordId: $id,
            recordIdentifier: $code
        );

        return redirect()->route('examinations.index')
            ->with('success', "Examination [{$code}] removed.");
    }
}
