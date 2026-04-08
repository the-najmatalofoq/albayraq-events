<?php
// modules/ViolationType/Presentation/Http/Request/CreateViolationTypeRequest.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class CreateViolationTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'               => ['required', 'array'],
            'name.en'            => ['required', 'string', 'max:255'],
            'name.ar'            => ['required', 'string', 'max:255'],
            'deduction_amount'   => ['nullable', 'numeric', 'min:0'],
            'deduction_currency' => ['nullable', 'string', 'size:3'],
            'severity'           => ['required', 'string', 'in:low,medium,high'],
            'event_id'           => ['sometimes', 'nullable', 'uuid'],
            'is_active'          => ['sometimes', 'boolean'],
        ];
    }
}
