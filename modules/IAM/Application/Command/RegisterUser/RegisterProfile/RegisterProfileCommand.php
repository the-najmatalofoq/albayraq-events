<?php
// modules/IAM/Application/Command/RegisterUser/RegisterProfile/RegisterProfileCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterProfile;

final readonly class RegisterProfileCommand
{
    public function __construct(
        public string $userId,
        public ?string $birthDate = null,
        public ?string $nationality = null,
        public ?string $gender = null,
        public ?float $height = null,
        public ?float $weight = null,
    ) {}
}
