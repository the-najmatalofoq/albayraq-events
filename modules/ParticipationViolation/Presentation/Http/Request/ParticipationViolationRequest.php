<?php
// modules/ParticipationViolation/Presentation/Http/Request/ParticipationViolationRequest.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class ParticipationViolationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'violation_type_id' => ['required', 'uuid', 'exists:violation_types,id'],
            'date'              => ['required', 'date'],
            'deduction_type_id' => ['nullable', 'uuid', 'exists:deduction_types,id'],
            'penalty_type_id'   => ['nullable', 'uuid', 'exists:penalty_types,id'],
            'description'       => ['nullable', 'string'],
        ];

        if ($this->isMethod('POST')) {
            $rules['event_participation_id'] = ['required', 'uuid', 'exists:event_participations,id'];
            $rules['reported_by']            = ['required', 'uuid', 'exists:users,id'];
        }

        return $rules;
    }
}
