<?php
// modules/User/Application/Command/UpdateUserProfile/UpdateUserProfileCommand.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateUserProfile;

use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\ValueObject\UserId;

final readonly class UpdateUserProfileCommand
{
    public function __construct(
        public UserId $userId,
        public TranslatableText $fullName,
        public ?NationalityId $nationalityId = null,
        public ?string $birthDate = null,
        public ?string $identityNumber = null,
        public ?string $gender = null,
        public ?float $height = null,
        public ?float $weight = null,
    ) {
    }
}
