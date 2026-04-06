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
use Modules\User\Domain\Repository\EmployeeNationalityRepositoryInterface;

final class EloquentEmployeeProfileRepository implements EmployeeProfileRepositoryInterface
{
    public function __construct(
        private readonly EmployeeProfileModel $model,
        private readonly EmployeeProfileReflector $reflector,
    ) {}

    public function nextIdentity(): EmployeeProfileId
    {
        return EmployeeProfileId::generate();
    }

    public function findById(EmployeeProfileId $id): ?EmployeeProfile
    {
        $model = $this->model->with('nationality')->find($id->value);

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function findByUserId(UserId $userId): ?EmployeeProfile
    {
        $model = $this->model->with('nationality')
            ->where('user_id', $userId->value)
            ->first();

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function save(EmployeeProfile $profile): void
    {
        $data = $this->reflector->fromEntity($profile);

        /** @var EmployeeProfileModel $model */
        $model = $this->model->withTrashed()->find($profile->uuid->value) ?? new EmployeeProfileModel();

        $model->id = $profile->uuid->value;
        $model->fill($data);
        $model->save();
    }

    public function delete(EmployeeProfileId $id): void
    {
        $this->model->destroy($id->value);
    }
}
