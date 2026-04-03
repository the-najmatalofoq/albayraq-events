<?php
// modules/User/Application/Command/UpdateUserProfile/UpdateUserProfileCommand.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateUserProfile;

final readonly class UpdateUserProfileCommand
{
    public function __construct(
        public string $userId,
        public ?string $nationalId = null,
        public ?string $birthDate = null,
        public ?string $nationality = null,
        public ?string $gender = null,
        public ?float $height = null,
        public ?float $weight = null,
    ) {
    }
}
