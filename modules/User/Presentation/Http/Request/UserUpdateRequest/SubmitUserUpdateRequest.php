<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request\UserUpdateRequest;

use Illuminate\Foundation\Http\FormRequest;

final class SubmitUserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'target_type' => ['required', 'string'],
            'target_id' => ['required', 'uuid'],
            'new_data' => ['required', 'array'],
        ];
    }
}
