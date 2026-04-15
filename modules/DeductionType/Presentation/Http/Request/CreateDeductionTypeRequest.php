<?php
// modules/DeductionType/Presentation/Http/Request/CreateDeductionTypeRequest.php
declare(strict_types=1);

namespace Modules\DeductionType\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class CreateDeductionTypeRequest extends FormRequest
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
            'slug'               => ['required', 'string', 'max:255', 'unique:deduction_types,slug'],
            'is_active'          => ['sometimes', 'boolean'],
        ];
    }
}
