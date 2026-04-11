<?php
// modules/User/Domain/MedicalRecord.php
declare(strict_types=1);

namespace Modules\User\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\Entity;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\Enum\BloodTypeEnum;
use Modules\User\Domain\ValueObject\MedicalRecordId;
use Modules\User\Domain\ValueObject\UserId;

final class MedicalRecord extends Entity
{
    private function __construct(
        public readonly MedicalRecordId $uuid,
        public readonly UserId $userId,
        public private(set) BloodTypeEnum $bloodType,
        public private(set) ?string $chronicDiseases,
        public private(set) ?string $allergies,
        public private(set) ?string $medications,
        public readonly ?DateTimeImmutable $createdAt = null,
        public private(set) ?DateTimeImmutable $updatedAt = null,
    ) {
    }

    public static function create(
        MedicalRecordId $uuid,
        UserId $userId,
        BloodTypeEnum $bloodType,
        ?string $chronicDiseases = null,
        ?string $allergies = null,
        ?string $medications = null,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            bloodType: $bloodType,
            chronicDiseases: $chronicDiseases,
            allergies: $allergies,
            medications: $medications,
            createdAt: new DateTimeImmutable(),
        );
    }

    public static function fromPersistence(
        MedicalRecordId $uuid,
        UserId $userId,
        BloodTypeEnum $bloodType,
        ?string $chronicDiseases,
        ?string $allergies,
        ?string $medications,
        ?DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            bloodType: $bloodType,
            chronicDiseases: $chronicDiseases,
            allergies: $allergies,
            medications: $medications,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function update(
        BloodTypeEnum $bloodType,
        ?string $chronicDiseases,
        ?string $allergies,
        ?string $medications,
    ): void {
        $this->bloodType = $bloodType;
        $this->chronicDiseases = $chronicDiseases;
        $this->allergies = $allergies;
        $this->medications = $medications;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
