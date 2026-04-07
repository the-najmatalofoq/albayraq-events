<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProfileRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'identity_number' => ['required', 'string', 'max:20'],
            'nationality_id' => ['nullable', 'string', 'uuid'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'height' => ['nullable', 'numeric', 'min:50', 'max:250'],
            'weight' => ['nullable', 'numeric', 'min:20', 'max:300'],
        ];
    }
}
