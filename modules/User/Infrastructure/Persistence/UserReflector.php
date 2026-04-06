<?php
// modules/User/Infrastructure/Persistence/UserReflector.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Shared\Domain\ValueObject\FilePath;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\HashedPassword;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;

final class UserReflector
{
    public function fromEntity(User $user): array
    {
        return [
            'id' => $user->uuid->value,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone->value,
            'password' => $user->password->value,
            'avatar' => $user->avatar?->value,
            'created_at' => $user->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $user->updatedAt?->format('Y-m-d H:i:s'),
            'deleted_at' => $user->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function toEntity(UserModel $model): User
    {
        $roleIds = [];
        if ($model->relationLoaded('roles')) {
            $roleIds = $model->roles->map(fn($role) => new RoleId($role->id))->toArray();
        }

        $isActive = false;
        if ($model->relationLoaded('latestJoinRequest') && $model->latestJoinRequest) {
            // $isActive = $model->latestJoinRequest->status->isActive();
        }

        /** @var TranslatableText $name */
        $name = $model->name;

        return User::reconstitute(
            uuid: new UserId($model->id),
            name: $name,
            email: $model->email,
            phone: new Phone($model->phone),
            password: new HashedPassword($model->password),
            roleIds: $roleIds,
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            avatar: $model->avatar ? new FilePath($model->avatar) : null,
            updatedAt: $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null,
            deletedAt: $model->deleted_at ? new DateTimeImmutable($model->deleted_at->toDateTimeString()) : null,
        );
    }
}
