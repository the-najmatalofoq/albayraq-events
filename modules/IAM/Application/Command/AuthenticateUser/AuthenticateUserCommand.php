<?php
// modules/IAM/Application/Command/AuthenticateUser/AuthenticateUserCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\AuthenticateUser;

final readonly class AuthenticateUserCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public ?string $fcmToken = null,
        public ?string $deviceId = null,
        public ?string $platform = null,
        public ?string $deviceName = null,
    ) {}
}
