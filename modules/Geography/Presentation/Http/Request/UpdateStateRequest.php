<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateStateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'country_id' => ['sometimes', 'uuid', 'exists:countries,id'],
            'name' => ['sometimes', 'array'],
            'name.en' => ['sometimes', 'string', 'max:255'],
            'name.ar' => ['nullable', 'string', 'max:255'],
        ];
    }
}
