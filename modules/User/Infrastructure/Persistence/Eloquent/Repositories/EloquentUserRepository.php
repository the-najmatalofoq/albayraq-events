<?php
// modules/User/Infrastructure/Persistence/Eloquent/Repositories/EloquentUserRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\User\Infrastructure\Persistence\UserReflector;

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

    public function findByPhone(string $phone): ?User
    {
        $model = $this->model->with('roles')->where('phone', $phone)->first();

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $model = $this->model->with('roles')->where('email', $email)->first();

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function save(User $user): void
    {
        $data = $this->reflector->fromEntity($user);
        $roleIds = array_map(fn($roleId) => $roleId->value, $user->roleIds);

        $modelInstance = clone $this->model;
        $model = $modelInstance->updateOrCreate(['id' => $user->uuid->value], $data);
        $model->roles()->sync($roleIds);
    }

    public function existsWithPhone(string $phone): bool
    {
        return $this->model->where('phone', $phone)->exists();
    }

    public function existsWithEmail(string $email): bool
    {
        return $this->model->where('email', $email)->exists();
    }

    public function delete(UserId $id): void
    {
        $this->model->destroy($id->value);
    }
}
