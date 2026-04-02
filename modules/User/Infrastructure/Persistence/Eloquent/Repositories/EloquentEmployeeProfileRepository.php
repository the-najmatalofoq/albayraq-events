<?php
// modules/User/Infrastructure/Persistence/Eloquent/EloquentEmployeeProfileRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\EmployeeProfileId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\EmployeeProfileModel;

final class EloquentEmployeeProfileRepository implements EmployeeProfileRepositoryInterface
{
    public function save(EmployeeProfile $profile): void
    {
        EmployeeProfileModel::query()->updateOrCreate(
            ['id' => $profile->uuid->value],
            [
                'user_id' => $profile->userId->value,
                'full_name' => $profile->fullName?->toArray(),
                'birth_date' => $profile->birthDate?->format('Y-m-d'),
                'nationality' => $profile->nationality,
                'gender' => $profile->gender,
                'national_id' => $profile->nationalId,
                'medical_record' => $profile->medicalRecord,
                'height' => $profile->height,
                'weight' => $profile->weight,
            ]
        );
    }

    public function findByUserId(UserId $userId): ?EmployeeProfile
    {
        $model = EmployeeProfileModel::where('user_id', $userId->value)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findById(EmployeeProfileId $uuid): ?EmployeeProfile
    {
        $model = EmployeeProfileModel::find($uuid->value);

        return $model ? $this->toDomain($model) : null;
    }

    public function nextIdentity(): EmployeeProfileId
    {
        return EmployeeProfileId::generate();
    }

    private function toDomain(EmployeeProfileModel $model): EmployeeProfile
    {
        $reflection = new \ReflectionClass(EmployeeProfile::class);
        $profile = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => EmployeeProfileId::fromString($model->id),
            'userId' => UserId::fromString($model->user_id),
            'fullName' => $model->full_name ? TranslatableText::fromArray($model->full_name) : null,
            'birth_date' => $model->birth_date ? \DateTimeImmutable::createFromMutable($model->birth_date) : null,
            'nationality' => $model->nationality,
            'gender' => $model->gender,
            'nationalId' => $model->national_id,
            'medicalRecord' => $model->medical_record,
            'height' => $model->height,
            'weight' => $model->weight,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($profile, $value);
        }

        return $profile;
    }
}
