<?php
// modules/User/Infrastructure/Persistence/Eloquent/Repositories/EloquentEmployeeProfileRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\ValueObject\EmployeeProfileId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\EmployeeProfileReflector;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\EmployeeProfileModel;

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

    public function listAll(): array
    {
        return $this->model->with('nationality')->get()
            ->map(fn(EmployeeProfileModel $model) => $this->reflector->toEntity($model))
            ->toArray();
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with('nationality');

        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->getCollection()->transform(fn(EmployeeProfileModel $model) => $this->reflector->toEntity($model));

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = $this->model->with('nationality');

        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(EmployeeProfileModel $model) => $this->reflector->toEntity($model));
    }

    private function applyCriteria($query, FilterCriteria $criteria): void
    {
        if ($criteria->search) {
            $query->where(function ($q) use ($criteria) {
                $q->where('full_name', 'like', "%{$criteria->search}%")
                    ->orWhere('identity_number', 'like', "%{$criteria->search}%");
            });
        }

        if ($criteria->sortBy) {
            $query->orderBy($criteria->sortBy, $criteria->sortDirection ?? 'asc');
        }
    }

    public function delete(EmployeeProfileId $id): void
    {
        $this->model->destroy($id->value);
    }
}
