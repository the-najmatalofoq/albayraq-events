<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAuth/RegisterAuthCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAuth;

final readonly class RegisterAuthCommand
{
    public function __construct(
        public string $userId,
        public array|string $name,
        public ?string $email,
        public string $phone,
        public string $password,
        public string $nationalId,
    ) {}
}
