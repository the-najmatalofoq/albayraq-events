<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class CreateCityRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'country_id' => ['required', 'uuid', 'exists:countries,id'],
            'state_id' => ['nullable', 'uuid', 'exists:states,id'],
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['nullable', 'string', 'max:255'],
        ];
    }
}
