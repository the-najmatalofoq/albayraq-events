<?php
// modules/User/Infrastructure/Persistence/Eloquent/Repositories/EloquentEmployeeProfileRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\ValueObject\EmployeeProfileId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\EmployeeProfileModel;
use Modules\User\Infrastructure\Persistence\EmployeeProfileReflector;

final class EloquentEmployeeProfileRepository implements EmployeeProfileRepositoryInterface
{
    public function __construct(
        private readonly EmployeeProfileReflector $reflector,
    ) {
    }

    public function nextIdentity(): EmployeeProfileId
    {
        return EmployeeProfileId::generate();
    }

    public function findById(EmployeeProfileId $id): ?EmployeeProfile
    {
        $model = EmployeeProfileModel::find($id->value);

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function findByUserId(UserId $userId): ?EmployeeProfile
    {
        $model = EmployeeProfileModel::where('user_id', $userId->value)->first();

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function save(EmployeeProfile $profile): void
    {
        $data = $this->reflector->fromEntity($profile);

        EmployeeProfileModel::updateOrCreate(
            ['id' => $profile->uuid->value],
            $data
        );
    }

    public function delete(EmployeeProfileId $id): void
    {
        EmployeeProfileModel::destroy($id->value);
    }
}
