<?php

declare(strict_types=1);

namespace Modules\Notification\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class RegisterDeviceTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'platform' => ['required', 'string', 'in:ios,android,web'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
