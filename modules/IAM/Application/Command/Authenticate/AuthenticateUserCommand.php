<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\Authenticate;

final readonly class AuthenticateUserCommand
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
