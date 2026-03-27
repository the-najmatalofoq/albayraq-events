<?php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Request;

use Modules\Shared\Presentation\Validation\InputValidator;
use Psr\Http\Message\ServerRequestInterface;

final readonly class RegisterRequest
{
    public function __construct(
        private InputValidator $validator,
    ) {}

    public function validated(ServerRequestInterface $request): array
    {
        return $this->validator->validate(
            (array) $request->getParsedBody(),
            [
                'name'     => ['required', 'array'],
                'email'    => ['nullable', 'email', 'max:255'],
                'phone'    => ['required', 'string', 'max:20', 'unique:users,phone'],
                'password' => ['required', 'string', 'min:8'],
            ]
        );
    }
}
