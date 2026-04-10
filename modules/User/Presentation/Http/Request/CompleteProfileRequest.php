<?php
// modules\User\Presentation\Http\Request\CompleteProfileRequest.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request;

use Illuminate\Http\Request;
use Modules\Shared\Presentation\Validation\InputValidator;

final readonly class CompleteProfileRequest
{
    public function __construct(
        private InputValidator $validator,
    ) {
    }

    public function validated(Request $request): array
    {
        return $this->validator->validate(
            (array) $request->all(),
            [
                'full_name' => ['required', 'json'],
                'birth_date' => ['required', 'date', 'before:today'],
                'nationality' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'in:male,female,other'],
                'national_id' => ['required', 'string', 'unique:employee_profiles,national_id', 'regex:/^\d{10}$/'],
                'medical_record' => ['nullable', 'json'],
                'height' => ['nullable', 'numeric', 'min:50', 'max:250'],
                'weight' => ['nullable', 'numeric', 'min:20', 'max:300'],
                'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
                'identity_personal' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            ]
        );
    }
}