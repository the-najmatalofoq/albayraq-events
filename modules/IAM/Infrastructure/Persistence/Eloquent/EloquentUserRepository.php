<?php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Persistence\Eloquent;

use Modules\IAM\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\User;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\IAM\Infrastructure\Persistence\UserReflector;

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
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'password' => $user->password->value,
                'is_active' => $user->isActive,
                'created_at' => $user->createdAt,
                'updated_at' => $user->updatedAt,
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
