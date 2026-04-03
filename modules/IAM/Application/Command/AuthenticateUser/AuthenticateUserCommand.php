<?php
// modules/IAM/Application/Command/AuthenticateUser/AuthenticateUserCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\AuthenticateUser;

final readonly class AuthenticateUserCommand
{
    public function __construct(
        public string $phone,
        public string $password,
    ) {
    }
}
