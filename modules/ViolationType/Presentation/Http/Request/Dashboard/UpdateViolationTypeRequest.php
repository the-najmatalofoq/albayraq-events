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
        return [
            'name' => ['sometimes', 'array'],
            'name.ar' => ['required_with:name', 'string', 'max:255'],
            'name.en' => ['required_with:name', 'string', 'max:255'],
            'default_deduction_amount' => ['sometimes', 'numeric', 'min:0'],
            'default_deduction_currency' => ['required_with:default_deduction_amount', 'string', 'size:3'],
            'severity' => ['sometimes', Rule::enum(ViolationSeverityEnum::class)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
