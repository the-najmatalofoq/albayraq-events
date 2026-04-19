<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateMedicalReportRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'blood_type'      => ['required', 'string', Rule::in([
                'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-',
            ])],
            'chronic_diseases' => ['nullable', 'string', 'max:1000'],
            'allergies'        => ['nullable', 'string', 'max:1000'],
            'medications'      => ['nullable', 'string', 'max:1000'],
        ];
    }
}
