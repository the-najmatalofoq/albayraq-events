<?php
// modules/PenaltyType/Presentation/Http/Request/UpdatePenaltyTypeRequest.php
declare(strict_types=1);

namespace Modules\PenaltyType\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UpdatePenaltyTypeRequest extends FormRequest
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
            'slug'               => ['sometimes', 'string', 'max:255', "unique:penalty_types,slug,{$id}"],
            'is_active'          => ['sometimes', 'boolean'],
        ];
    }
}
