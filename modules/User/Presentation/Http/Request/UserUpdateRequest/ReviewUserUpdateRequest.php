<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request\UserUpdateRequest;

use Illuminate\Foundation\Http\FormRequest;

final class ReviewUserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'string', 'in:approve,reject'],
            'rejection_reason' => ['required_if:action,reject', 'string', 'nullable'],
        ];
    }
}
