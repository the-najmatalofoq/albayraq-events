<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class BulkDeleteContactPhonesRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'string', 'uuid'],
        ];
    }
}
