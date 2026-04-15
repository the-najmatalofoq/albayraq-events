<?php
// modules/DeductionType/Presentation/Http/Request/UpdateDeductionTypeRequest.php
declare(strict_types=1);

namespace Modules\DeductionType\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateDeductionTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name'               => ['sometimes', 'array'],
            'name.en'            => ['required_with:name', 'string', 'max:255'],
            'name.ar'            => ['required_with:name', 'string', 'max:255'],
            'slug'               => ['sometimes', 'string', 'max:255', "unique:deduction_types,slug,{$id}"],
            'is_active'          => ['sometimes', 'boolean'],
        ];
    }
}
