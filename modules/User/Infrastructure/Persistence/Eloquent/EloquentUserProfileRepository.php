<?php
// modules/User/Infrastructure/Persistence/Eloquent/EloquentUserProfileRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent;

use Modules\User\Domain\UserProfile;
use Modules\User\Domain\ValueObject\UserProfileId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\User\Domain\Repository\UserProfileRepositoryInterface;
use Modules\User\Infrastructure\Persistence\UserProfileReflector;

final class EloquentUserProfileRepository implements UserProfileRepositoryInterface
{
    public function nextIdentity(): UserProfileId
    {
        return UserProfileId::generate();
    }

    public function save(UserProfile $userProfile): void
    {
        UserProfileModel::updateOrCreate(
            ['id' => $userProfile->uuid->value],
            [
                'user_id' => $userProfile->userId->value,
                'employee_number' => $userProfile->employeeNumber,
                'job_title' => $userProfile->jobTitle->toArray(),
                'department' => $userProfile->department->toArray(),
                'hiring_date' => $userProfile->hiringDate?->format('Y-m-d'),
                'is_active' => $userProfile->isActive,
            ]
        );
    }

    public function findById(UserProfileId $id): ?UserProfile
    {
        $model = UserProfileModel::find($id->value);
        return $model ? UserProfileReflector::fromModel($model) : null;
    }

    public function findByUserId(UserId $userId): ?UserProfile
    {
        $model = UserProfileModel::where('user_id', $userId->value)->first();
        return $model ? UserProfileReflector::fromModel($model) : null;
    }

    public function findByEmployeeNumber(string $employeeNumber): ?UserProfile
    {
        $model = UserProfileModel::where('employee_number', $employeeNumber)->first();
        return $model ? UserProfileReflector::fromModel($model) : null;
    }

    public function listAll(): array
    {
        return UserProfileModel::all()
            ->map(fn($model) => UserProfileReflector::fromModel($model))
            ->toArray();
    }
}
