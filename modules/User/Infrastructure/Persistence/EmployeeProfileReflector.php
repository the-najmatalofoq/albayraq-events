<?php
// modules/User/Infrastructure/Persistence/EmployeeProfileReflector.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
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
            'full_name' => $profile->fullName,
            'identity_number' => $profile->identityNumber,
            'nationality_id' => $profile->nationalityId->value,
            'birth_date' => $profile->birthDate?->format('Y-m-d'),
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
        /** @var TranslatableText $fullName */
        $fullName = $model->full_name;

        return EmployeeProfile::reconstitute(
            uuid: new EmployeeProfileId($model->id),
            userId: new UserId($model->user_id),
            fullName: $fullName,
            identityNumber: $model->identity_number,
            nationalityId: new NationalityId($model->nationality_id),
            birthDate: $model->birth_date ? new DateTimeImmutable($model->birth_date->toDateTimeString()) : null,
            gender: $model->gender ? GenderEnum::from($model->gender) : null,
            height: $model->height !== null ? (float) $model->height : null,
            weight: $model->weight !== null ? (float) $model->weight : null,
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            updatedAt: $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null,
            deletedAt: $model->deleted_at ? new DateTimeImmutable($model->deleted_at->toDateTimeString()) : null,
        );
    }
}
