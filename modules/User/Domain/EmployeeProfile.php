<?php
// modules/User/Domain/EmployeeProfile.php
declare(strict_types=1);

namespace Modules\User\Domain;

use DateTimeImmutable;
use Modules\User\Domain\ValueObject\EmployeeProfileId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\Enum\Gender;

// fix: we must make the Gender Enum, and we must name it GenderEnum file and the enum must have two values only, and we must name the Enum as GenderEnum
final class EmployeeProfile extends AggregateRoot
{
    private function __construct(
        public readonly EmployeeProfileId $uuid,
        public readonly UserId $userId,
        public readonly ?DateTimeImmutable $birthDate,
        public readonly ?string $nationality,
        public readonly ?Gender $gender,
        public readonly ?float $height,
        public readonly ?float $weight,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?DateTimeImmutable $updatedAt = null,
        public private(set) ?DateTimeImmutable $deletedAt = null,
    ) {
    }

    public static function create(
        EmployeeProfileId $uuid,
        UserId $userId,
        ?DateTimeImmutable $birthDate,
        ?string $nationality,
        ?Gender $gender,
        ?float $height,
        ?float $weight,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            birthDate: $birthDate,
            nationality: $nationality,
            gender: $gender,
            height: $height,
            weight: $weight,
            createdAt: $createdAt,
        );
    }

    public static function reconstitute(
        EmployeeProfileId $uuid,
        UserId $userId,
        ?DateTimeImmutable $birthDate,
        ?string $nationality,
        ?Gender $gender,
        ?float $height,
        ?float $weight,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt = null,
        ?DateTimeImmutable $deletedAt = null,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            birthDate: $birthDate,
            nationality: $nationality,
            gender: $gender,
            height: $height,
            weight: $weight,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt,
        );
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
