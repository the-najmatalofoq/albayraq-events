<?php
// modules/User/Infrastructure/Persistence/Eloquent/EloquentUserRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\UserReflector;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function nextIdentity(): UserId
    {
        return UserId::generate();
    }

    public function save(User $user): void
    {
        $model = UserModel::updateOrCreate(
            ['id' => $user->uuid->value],
            [
                'name' => $user->name->toArray(),
                'email' => $user->email,
                'phone' => $user->phone->value,
                'avatar' => $user->avatar?->value,
                'password' => $user->password->value,
                'is_active' => $user->isActive,
                'created_at' => $user->createdAt,
                'updated_at' => $user->updatedAt,
                'deleted_at' => $user->deletedAt,
            ]
        );

        $roleIds = array_map(fn($roleId) => $roleId->value, $user->roleIds);
        $model->roles()->sync($roleIds);
    }

    public function findById(UserId $id): ?User
    {
        $model = UserModel::with('roles')->where('id', $id->value)->first();

        return $model ? UserReflector::fromModel($model) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $model = UserModel::with('roles')->where('email', $email)->first();
        return $model ? UserReflector::fromModel($model) : null;
    }

    public function findByPhone(string $phone): ?User
    {
        $model = UserModel::with('roles')->where('phone', $phone)->first();
        return $model ? UserReflector::fromModel($model) : null;
    }
}
