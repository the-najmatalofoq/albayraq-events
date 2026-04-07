<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class AddContactPhoneRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'relation' => ['required', 'string', 'max:100'],
        ];
    }
}
