<?php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Request;

use Modules\Shared\Presentation\Validation\InputValidator;
use Psr\Http\Message\ServerRequestInterface;

final readonly class LoginRequest
{
    public function __construct(
        private InputValidator $validator,
    ) {}

    public function validated(ServerRequestInterface $request): array
    {
        return $this->validator->validate(
            (array) $request->getParsedBody(),
            [
                'email'    => ['required', 'email'],
                'password' => ['required', 'string'],
            ]
        );
    }
}
