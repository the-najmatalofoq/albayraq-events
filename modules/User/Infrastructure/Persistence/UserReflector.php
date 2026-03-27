<?php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence;

use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\HashedPassword;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\User\Infrastructure\Persistence\Eloquent\UserModel;

final class UserReflector
{
    public static function fromModel(UserModel $model): User
    {
        $reflection = new \ReflectionClass(User::class);
        $user = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => UserId::fromString($model->id),
            'name' => $model->name,
            'email' => $model->email,
            'phone' => $model->phone,
            'password' => new HashedPassword($model->password),
            'roleIds' => [], // will be set separately
            'isActive' => $model->is_active,
            'createdAt' => $model->created_at->toDateTimeImmutable(),
            'updatedAt' => $model->updated_at?->toDateTimeImmutable(),
            'phoneVerifiedAt' => $model->phone_verified_at?->toDateTimeImmutable(),
            'deletedAt' => $model->deleted_at?->toDateTimeImmutable(),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($user, $value);
        }

        $roleIds = [];
        foreach ($model->roles as $roleModel) {
            $roleIds[] = RoleId::fromString($roleModel->uuid ?? $roleModel->id);
        }
        $prop = $reflection->getProperty('roleIds');
        $prop->setValue($user, $roleIds);

        return $user;
    }
}
