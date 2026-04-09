<?php
// modules/IAM/Application/Command/RegisterUser/RegisterProfile/RegisterProfileCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterProfile;

use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class RegisterProfileCommand
{
    public function __construct(
        public UserId $userId,
        public TranslatableText $fullName,
        public string $identityNumber,
        public NationalityId $nationalityId,
        public ?string $birthDate = null,
        public ?string $gender = null,
        public ?float $height = null,
        public ?float $weight = null,
    ) {}
}
