<?php
// modules/IAM/Presentation/Http/Request/RegisterRequest.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\Shared\Infrastructure\Validation\Rules\SaudiPhoneRule;
use Modules\Shared\Infrastructure\Validation\Rules\TranslatableJsonRule;
use Modules\Shared\Infrastructure\ValidationRule\IbanRule;

final class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // fix: name must be strig and to locale must handled in the actin
        return [
            'name' => ['required', new TranslatableJsonRule()],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', new SaudiPhoneRule(), 'unique:users,phone'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'national_id' => ['required', 'string', 'unique:users,national_id'],

            'birth_date' => ['nullable', 'date', 'before:today'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'height' => ['nullable', 'numeric', 'min:50', 'max:300'],
            'weight' => ['nullable', 'numeric', 'min:20', 'max:500'],

            'account_owner' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'iban' => ['required', 'string', new IbanRule(), 'unique:bank_details,iban'],

            'contact_phones' => ['nullable', 'array'],
            'contact_phones.*.label' => ['required_with:contact_phones', 'string', 'max:50'],
            'contact_phones.*.phone' => ['required_with:contact_phones', new SaudiPhoneRule()],

            'avatar' => ['nullable', 'image', 'max:2048'],
            'id_copy' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
