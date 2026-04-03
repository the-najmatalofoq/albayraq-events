<?php
// modules/IAM/Application/Command/RegisterUser/RegisterContactPhone/RegisterContactPhoneCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone;

final readonly class RegisterContactPhoneCommand
{
    public function __construct(
        public string $userId,
        public string $label,
        public string $phone,
    ) {}
}
