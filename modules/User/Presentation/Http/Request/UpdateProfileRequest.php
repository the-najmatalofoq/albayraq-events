<?php
// modules/User/Presentation/Http/Request/UpdateProfileRequest.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'national_id' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'city_id' => ['nullable', 'uuid', 'exists:cities,id'],
            'nationalities' => ['nullable', 'array'],
            'nationalities.*.id' => ['required_with:nationalities', 'uuid', 'exists:nationalities,id'],
            'nationalities.*.is_primary' => ['required_with:nationalities', 'boolean'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'height' => ['nullable', 'numeric', 'min:50', 'max:300'],
            'weight' => ['nullable', 'numeric', 'min:20', 'max:500'],
        ];
    }
}
