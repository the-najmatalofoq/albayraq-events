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
            'is_active'          => ['sometimes', 'boolean'],
        ];
    }
}
