<?php
// modules/User/Infrastructure/Persistence/EmployeeProfileReflector.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\Enum\GenderEnum;
use Modules\User\Domain\ValueObject\EmployeeProfileId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\EmployeeProfileModel;

final class EmployeeProfileReflector
{
    public function fromEntity(EmployeeProfile $profile): array
    {
        return [
            'id' => $profile->uuid->value,
            'user_id' => $profile->userId->value,
            'birth_date' => $profile->birthDate?->format('Y-m-d'),
            'nationality' => $profile->nationality,
            'gender' => $profile->gender?->value,
            'height' => $profile->height,
            'weight' => $profile->weight,
            'created_at' => $profile->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $profile->updatedAt?->format('Y-m-d H:i:s'),
            'deleted_at' => $profile->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function toEntity(EmployeeProfileModel $model): EmployeeProfile
    {
        return EmployeeProfile::reconstitute(
            uuid: new EmployeeProfileId($model->id),
            userId: new UserId($model->user_id),
            birthDate: $model->birth_date ? new DateTimeImmutable($model->birth_date->toDateTimeString()) : null,
            nationality: $model->nationality,
            gender: $model->gender ? GenderEnum::from($model->gender) : null,
            height: $model->height !== null ? (float) $model->height : null,
            weight: $model->weight !== null ? (float) $model->weight : null,
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            updatedAt: $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null,
            deletedAt: $model->deleted_at ? new DateTimeImmutable($model->deleted_at->toDateTimeString()) : null,
        );
    }
}
