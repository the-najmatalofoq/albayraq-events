<?php
// modules/User/Domain/EmployeeProfile.php
declare(strict_types=1);

namespace Modules\User\Domain;

use DateTimeImmutable;
use Modules\User\Domain\ValueObject\EmployeeProfileId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\Enum\GenderEnum;
use Modules\User\Domain\ValueObject\EmployeeNationality;
use Modules\Geography\Domain\ValueObject\CityId;

final class EmployeeProfile extends AggregateRoot
{
    /** @param EmployeeNationality[] $nationalities */
    private function __construct(
        public readonly EmployeeProfileId $uuid,
        public readonly UserId $userId,
        public readonly ?DateTimeImmutable $birthDate,
        public readonly ?CityId $cityId,
        public readonly array $nationalities,
        public readonly ?GenderEnum $gender,
        public readonly ?float $height,
        public readonly ?float $weight,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?DateTimeImmutable $updatedAt = null,
        public private(set) ?DateTimeImmutable $deletedAt = null,
    ) {
    }

    /** @param EmployeeNationality[] $nationalities */
    public static function create(
        EmployeeProfileId $uuid,
        UserId $userId,
        ?DateTimeImmutable $birthDate,
        ?CityId $cityId,
        array $nationalities,
        ?GenderEnum $gender,
        ?float $height,
        ?float $weight,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            birthDate: $birthDate,
            cityId: $cityId,
            nationalities: $nationalities,
            gender: $gender,
            height: $height,
            weight: $weight,
            createdAt: $createdAt,
        );
    }

    /** @param EmployeeNationality[] $nationalities */
    public static function reconstitute(
        EmployeeProfileId $uuid,
        UserId $userId,
        ?DateTimeImmutable $birthDate,
        ?CityId $cityId,
        array $nationalities,
        ?GenderEnum $gender,
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
            cityId: $cityId,
            nationalities: $nationalities,
            gender: $gender,
            height: $height,
            weight: $weight,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt,
        );
    }

    public function update(
        ?DateTimeImmutable $birthDate,
        ?CityId $cityId,
        array $nationalities,
        ?GenderEnum $gender,
        ?float $height,
        ?float $weight,
    ): void {
        $this->instance_variable_copy([
            'birthDate' => $birthDate,
            'cityId' => $cityId,
            'nationalities' => $nationalities,
            'gender' => $gender,
            'height' => $height,
            'weight' => $weight,
            'updatedAt' => new DateTimeImmutable(),
        ]);
    }

    private function instance_variable_copy(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
