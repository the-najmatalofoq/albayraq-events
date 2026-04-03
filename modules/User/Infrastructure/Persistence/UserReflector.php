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
            'name' => $user->name->toArray(),
            'email' => $user->email,
            'phone' => $user->phone->value,
            'password' => $user->password->value,
            'national_id' => $user->nationalId,
            'avatar' => $user->avatar?->value,
            'is_active' => $user->isActive,
            'created_at' => $user->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $user->updatedAt?->format('Y-m-d H:i:s'),
            'phone_verified_at' => $user->phoneVerifiedAt?->format('Y-m-d H:i:s'),
            'deleted_at' => $user->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function toEntity(UserModel $model): User
    {
        $roleIds = [];
        if ($model->relationLoaded('roles')) {
            $roleIds = $model->roles->map(fn($role) => new RoleId($role->id))->toArray();
        }

        return User::reconstitute(
            uuid: new UserId($model->id),
            name: TranslatableText::fromArray($model->name),
            email: $model->email,
            phone: new Phone($model->phone),
            password: new HashedPassword($model->password),
            roleIds: $roleIds,
            isActive: (bool) $model->is_active,
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            nationalId: $model->national_id,
            avatar: $model->avatar ? new FilePath($model->avatar) : null,
            updatedAt: $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null,
            phoneVerifiedAt: $model->phone_verified_at ? new DateTimeImmutable($model->phone_verified_at->toDateTimeString()) : null,
            deletedAt: $model->deleted_at ? new DateTimeImmutable($model->deleted_at->toDateTimeString()) : null,
        );
    }
}
