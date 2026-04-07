<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shared\Infrastructure\Validation\Rules\SaudiPhoneRule;

final class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', new SaudiPhoneRule(), 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'full_name' => ['required', 'string', 'max:255'],
            'identity_number' => ['required', 'string', 'unique:employee_profiles,identity_number'],
            'nationality_id' => ['required', 'uuid', 'exists:nationalities,id'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'height' => ['nullable', 'numeric'],
            'weight' => ['nullable', 'numeric'],
            'account_owner' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'iban' => ['required', 'string', 'max:34'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', new SaudiPhoneRule()],
            'contact_relation' => ['required', 'string', 'max:50'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'personal_identity' => ['nullable', 'image', 'max:2048'],
            'medical_report' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
