<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shared\Infrastructure\Validation\Rules\PasswordRule;
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
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', new SaudiPhoneRule(), 'unique:users,phone'],
            'password' => ['required', 'string', new PasswordRule()],
            'avatar'=>['nullable', 'image', 'max:2048'],
            'role_id'=>['required', 'uuid', 'exists:roles,id'],

        ];
    }
}
