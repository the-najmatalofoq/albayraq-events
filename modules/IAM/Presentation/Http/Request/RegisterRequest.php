<?php
// modules/IAM/Presentation/Http/Request/RegisterRequest.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shared\Infrastructure\Validation\Rules\IbanRule;
use Modules\Shared\Infrastructure\Validation\Rules\SaudiPhoneRule;
use Modules\Shared\Infrastructure\Validation\Rules\PasswordRule;
use Modules\User\Domain\Enum\BloodTypeEnum;
use Illuminate\Validation\Rule;

final class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', new SaudiPhoneRule()],
            'password' => ['required', 'confirmed', new PasswordRule()],
            'full_name' => ['required', 'string'],
            'identity_number' => ['required', 'string', 'max:10'],
            'nationality_id' => ['required', 'uuid', 'exists:nationalities,id'],

            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'height' => ['nullable', 'numeric', 'min:50', 'max:300'],
            'weight' => ['nullable', 'numeric', 'min:20', 'max:500'],

            'account_owner' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'iban' => ['required', 'string', new IbanRule()],

            'contact_name' => ['required_with:contact_phone', 'string', 'max:255'],
            'contact_phone' => ['required_with:contact_name', new SaudiPhoneRule()],
            'contact_relation' => ['required_with:contact_name', 'string', 'max:255'],

            'blood_type' => ['required', Rule::enum(BloodTypeEnum::class)],
            'chronic_diseases' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'medications' => ['nullable', 'string'],

            'avatar' => ['required', 'image', 'max:1024'],
            'cv' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'personal_identity' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'medical_report' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
