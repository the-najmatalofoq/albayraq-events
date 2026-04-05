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
use Str;

final class EloquentEmployeeProfileRepository implements EmployeeProfileRepositoryInterface
{
    public function __construct(
        private readonly EmployeeProfileModel $model,
        private readonly EmployeeProfileReflector $reflector,
    ) {
    }

    public function nextIdentity(): EmployeeProfileId
    {
        return EmployeeProfileId::generate();
    }

    public function findById(EmployeeProfileId $id): ?EmployeeProfile
    {
        $model = $this->model->with('nationalities')->find($id->value);

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function findByUserId(UserId $userId): ?EmployeeProfile
    {
        $model = $this->model->with('nationalities')
            ->where('user_id', $userId->value)
            ->first();

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function save(EmployeeProfile $profile): void
    {
        $data = $this->reflector->fromEntity($profile);

        $model = $this->model->updateOrCreate(
            ['id' => $profile->uuid->value],
            $data
        );

        $syncData = [];
        foreach ($profile->nationalities as $nationality) {
            $syncData[$nationality->nationalityId->value] = [
                'id' => Str::uuid()->toString(),
                'is_primary' => $nationality->isPrimary,
            ];
        }

        $model->nationalities()->sync($syncData);
    }

    public function delete(EmployeeProfileId $id): void
    {
        $this->model->destroy($id->value);
    }
}
