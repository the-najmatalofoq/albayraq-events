<?php
// modules/IAM/Presentation/Http/Request/LoginRequest.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // fix: login must be with email
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }
}
