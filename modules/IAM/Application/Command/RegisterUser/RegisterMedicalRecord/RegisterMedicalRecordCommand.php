<?php
// modules/IAM/Application/Command/RegisterUser/RegisterMedicalRecord/RegisterMedicalRecordCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterMedicalRecord;

use Modules\User\Domain\Enum\BloodTypeEnum;
use Modules\User\Domain\ValueObject\UserId;

final readonly class RegisterMedicalRecordCommand
{
    public function __construct(
        public UserId $userId,
        public BloodTypeEnum $bloodType,
        public ?string $chronicDiseases = null,
        public ?string $allergies = null,
        public ?string $medications = null,
    ) {}
}
