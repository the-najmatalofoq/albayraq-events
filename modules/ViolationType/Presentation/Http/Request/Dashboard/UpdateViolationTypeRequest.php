<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ViolationType\Domain\Enum\ViolationSeverityEnum;

final class UpdateViolationTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'array'],
            'name.ar' => ['sometimes', 'string', 'max:255'],
            'name.en' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('violation_types', 'slug')->ignore($id)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
