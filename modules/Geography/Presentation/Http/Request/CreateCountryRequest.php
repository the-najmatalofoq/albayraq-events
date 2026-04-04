<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class CreateCountryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'size:2', 'unique:countries,code'],
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['nullable', 'string', 'max:255'],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
