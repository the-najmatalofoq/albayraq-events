<?php
// modules/ViolationType/Presentation/Http/Request/UpdateViolationTypeRequest.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateViolationTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'               => ['sometimes', 'array'],
            'name.en'            => ['required_with:name', 'string', 'max:255'],
            'name.ar'            => ['required_with:name', 'string', 'max:255'],
            'deduction_amount'   => ['nullable', 'numeric', 'min:0'],
            'deduction_currency' => ['nullable', 'string', 'size:3'],
            'severity'           => ['sometimes', 'string', 'in:low,medium,high'],
            'event_id'           => ['sometimes', 'nullable', 'uuid'],
            'is_active'          => ['sometimes', 'boolean'],
        ];
    }
}
