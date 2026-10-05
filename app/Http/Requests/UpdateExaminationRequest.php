<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExaminationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('examination.edit') ?? false;
    }

    public function rules(): array
    {
        return [
            'procedure_id' => ['required', 'exists:procedures,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'referring_doctor_id' => ['nullable', 'exists:referring_doctors,id'],
            'instrument_id' => ['nullable', 'exists:instruments,id'],
            'indication' => ['required', 'string'],
            'examination_date' => ['required', 'date'],
            'examination_time' => ['required'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:Scheduled,In Progress,Captured,Reporting,Reviewed,Finalized,Cancelled'],
        ];
    }
}
