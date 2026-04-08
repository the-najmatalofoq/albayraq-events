<?php
// modules/ViolationType/Presentation/Http/Request/ListViolationTypesPaginationRequest.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class ListViolationTypesPaginationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page'     => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'search'   => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
