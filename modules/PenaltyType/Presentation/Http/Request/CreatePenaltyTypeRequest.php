<?php
// modules/PenaltyType/Presentation/Http/Request/CreatePenaltyTypeRequest.php
declare(strict_types=1);

namespace Modules\PenaltyType\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class CreatePenaltyTypeRequest extends FormRequest
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
            'slug'               => ['required', 'string', 'max:255', 'unique:penalty_types,slug'],
            'is_active'          => ['sometimes', 'boolean'],
        ];
    }
}
