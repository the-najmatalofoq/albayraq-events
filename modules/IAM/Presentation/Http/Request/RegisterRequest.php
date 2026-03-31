<?php
// modules\IAM\Presentation\Http\Request\RegisterRequest.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Request;

// use Doctrine\Inflector\Rules\English\Rules;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Modules\Shared\Presentation\Validation\InputValidator;
// use Psr\Http\Message\ServerRequestInterface;

final readonly class RegisterRequest
{
    public function __construct(
        private InputValidator $validator,
    ) {}

    public function validated(Request $request): array
    {
        return $this->validator->validate(
            (array) $request->all(),
            [
                'name' => ['required', 'array'],
                'email' => ['nullable', 'email', 'max:255'],
                'phone' => ['required', 'string', 'regex:/^(?:\+966|966|0)?5\d{8}$/', 'unique:users,phone'],
                'password' => ['required', 'string', 'confirmed', Password::min(8)->uncompromised()
                    ->mixedCase()->numbers()->symbols()],
                'avatar' => ['nullable', 'image', 'max:1024', 'mimes:jpg,jpeg,png'],
            ]
        );
    }
}
