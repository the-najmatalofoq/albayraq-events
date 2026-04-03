<?php
// modules/IAM/Application/Command/AuthenticateUser/AuthenticateUserCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\AuthenticateUser;

use Modules\Shared\Application\Command\CommandInterface;
// fix: the CommandInterface.
final readonly class AuthenticateUserCommand implements CommandInterface
{
    public function __construct(
        public string $login,
        public string $password,
    ) {
    }
}
