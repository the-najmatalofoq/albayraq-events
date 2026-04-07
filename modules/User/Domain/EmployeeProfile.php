<?php

declare(strict_types=1);

namespace Modules\User\Domain;

use DateTimeImmutable;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\Shared\Domain\Entity;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\ValueObject\EmployeeProfileId;
use Modules\User\Domain\ValueObject\UserId;

final class EmployeeProfile extends Entity
{
    private function __construct(
        public readonly EmployeeProfileId $uuid,
        public readonly UserId $userId,
        public private(set) string $fullName,
        public private(set) string $identityNumber,
        public private(set) ?NationalityId $nationalityId,
        public private(set) ?string $birthDate,
        public private(set) ?string $gender,
        public private(set) ?float $height,
        public private(set) ?float $weight,
        public readonly ?DateTimeImmutable $createdAt = null,
        public private(set) ?DateTimeImmutable $updatedAt = null,
        public private(set) ?DateTimeImmutable $deletedAt = null,
    ) {
    }

    public static function create(
        EmployeeProfileId $uuid,
        UserId $userId,
        string $fullName,
        string $identityNumber,
        ?NationalityId $nationalityId = null,
        ?string $birthDate = null,
        ?string $gender = null,
        ?float $height = null,
        ?float $weight = null,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            fullName: $fullName,
            identityNumber: $identityNumber,
            nationalityId: $nationalityId,
            birthDate: $birthDate,
            gender: $gender,
            height: $height,
            weight: $weight,
            createdAt: new DateTimeImmutable(),
        );
    }

    public static function fromPersistence(
        EmployeeProfileId $uuid,
        UserId $userId,
        string $fullName,
        string $identityNumber,
        ?NationalityId $nationalityId,
        ?string $birthDate,
        ?string $gender,
        ?float $height,
        ?float $weight,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
        ?DateTimeImmutable $deletedAt = null,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            fullName: $fullName,
            identityNumber: $identityNumber,
            nationalityId: $nationalityId,
            birthDate: $birthDate,
            gender: $gender,
            height: $height,
            weight: $weight,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt,
        );
    }

    public function update(
        string $fullName,
        string $identityNumber,
        ?NationalityId $nationalityId,
        ?string $birthDate,
        ?string $gender,
        ?float $height,
        ?float $weight,
    ): void {
        $this->fullName = $fullName;
        $this->identityNumber = $identityNumber;
        $this->nationalityId = $nationalityId;
        $this->birthDate = $birthDate;
        $this->gender = $gender;
        $this->height = $height;
        $this->weight = $weight;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function softDelete(): void
    {
        $this->deletedAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}