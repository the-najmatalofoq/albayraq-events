<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shared\Infrastructure\Validation\Rules\SaudiPhoneRule;

final class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name' => ['string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email,' . $userId],
            'phone' => ['string', new SaudiPhoneRule(), 'unique:users,phone,' . $userId],
            'password' => ['nullable', 'string', 'min:8'],
            'full_name' => ['string', 'max:255'],
            'identity_number' => ['string', 'unique:employee_profiles,identity_number,' . $userId . ',user_id'],
            'nationality_id' => ['uuid', 'exists:nationalities,id'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'height' => ['nullable', 'numeric'],
            'weight' => ['nullable', 'numeric'],
        ];
    }
}
