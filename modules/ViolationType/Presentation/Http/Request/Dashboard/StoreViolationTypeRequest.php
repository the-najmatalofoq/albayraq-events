<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

final class StoreViolationTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.ar' => ['required', 'string', 'max:255'],
            'name.en' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:violation_types,slug'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
