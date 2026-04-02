<?php
// modules/User/Domain/EmployeeProfile.php
declare(strict_types=1);

namespace Modules\User\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\EmployeeProfileId;

final class EmployeeProfile extends AggregateRoot
{
    private function __construct(
        public readonly EmployeeProfileId $uuid,
        public readonly UserId $userId,
        public private(set) ?TranslatableText $fullName = null,
        public private(set) ?\DateTimeImmutable $birthDate = null,
        public private(set) ?string $nationality = null,
        public private(set) ?string $gender = null,
        public private(set) ?float $height = null,
        public private(set) ?float $weight = null,
    ) {}

    public static function create(
        EmployeeProfileId $uuid,
        UserId $userId,
    ): self {
        return new self($uuid, $userId);
    }

    public function updatePersonalData(
        TranslatableText $fullName,
        \DateTimeImmutable $birthDate,
        string $nationality,
        string $gender,
    ): void {
        $this->fullName = $fullName;
        $this->birthDate = $birthDate;
        $this->nationality = $nationality;
        $this->gender = $gender;
    }

    public function updatePhysicalData(
        float $height,
        float $weight,
    ): void {
        $this->height = $height;
        $this->weight = $weight;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
