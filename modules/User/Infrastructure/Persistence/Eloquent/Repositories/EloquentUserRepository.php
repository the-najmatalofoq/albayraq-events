<?php
// modules/User/Infrastructure/Persistence/Eloquent/Repositories/EloquentUserRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\User\Infrastructure\Persistence\UserReflector;
use Illuminate\Support\Collection;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly UserReflector $reflector,
        private readonly UserModel $model,
    ) {
    }

    public function nextIdentity(): UserId
    {
        return UserId::generate();
    }

    public function findById(UserId $id): ?User
    {
        $model = $this->model->with('roles')->find($id->value);

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function findByPhone(Phone $phone): ?User
    {
        $model = $this->model->with('roles')->where('phone', $phone->value)->first();

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $model = $this->model->with('roles')->where('email', $email)->first();

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function save(User $user, ?string $avatarPath = null): void
    {
        $data = $this->reflector->fromEntity($user);
        $roleIds = array_map(fn($roleId) => $roleId->value, $user->roleIds);

        /** @var UserModel $model */
        $model = $this->model->withTrashed()->find($user->uuid->value) ?? new UserModel();

        $model->id = $user->uuid->value;
        $model->fill($data);
        $model->save();

        $model->roles()->sync($roleIds);
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with('roles');

        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->getCollection()->transform(fn(UserModel $model) => $this->reflector->toEntity($model));

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = $this->model->with('roles');

        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(UserModel $model) => $this->reflector->toEntity($model));
    }

    private function applyCriteria($query, FilterCriteria $criteria): void
    {
        if ($criteria->search) {
            $query->where(function ($q) use ($criteria) {
                $q->where('name', 'like', "%{$criteria->search}%")
                    ->orWhere('email', 'like', "%{$criteria->search}%")
                    ->orWhere('phone', 'like', "%{$criteria->search}%");
            });
        }

        if ($criteria->sortBy) {
            $query->orderBy($criteria->sortBy, $criteria->sortDirection ?? 'asc');
        }
    }

    public function delete(UserId $id): void
    {
        $this->model->destroy($id->value);
    }
}
