<?php

declare(strict_types=1);

namespace Modules\Notification\Infrastructure\Persistence\Eloquent;

use Modules\Notification\Domain\DeviceToken;
use Modules\Notification\Domain\Repository\DeviceTokenRepositoryInterface;
use Modules\Notification\Domain\ValueObject\DeviceTokenId;
use Modules\Notification\Infrastructure\Persistence\Reflector\DeviceTokenReflector;
use Modules\User\Domain\ValueObject\UserId;

final class EloquentDeviceTokenRepository implements DeviceTokenRepositoryInterface
{
    public function save(DeviceToken $token): void
    {
        $model = DeviceTokenModel::findOrNew($token->getId()->value);
        $model->fill([
            'id' => $token->getId()->value,
            'user_id' => $token->getUserId()->value,
            'device_id' => $token->getDeviceId(),
            'token' => $token->getToken(),
            'platform' => $token->getPlatform(),
            'device_name' => $token->getDeviceName(),
            'is_active' => $token->isActive(),
            'last_used_at' => $token->getLastUsedAt(),
        ]);
        $model->save();
    }

    public function findById(DeviceTokenId $id): ?DeviceToken
    {
        $model = DeviceTokenModel::find($id->value);
        return $model ? DeviceTokenReflector::reverse($model) : null;
    }

    public function findByUser(UserId $userId): array
    {
        $models = DeviceTokenModel::where('user_id', $userId->value)
            ->where('is_active', true)
            ->get();

        return $models->map(fn($model) => DeviceTokenReflector::reverse($model))->toArray();
    }

    public function findByUserAndDevice(UserId $userId, string $deviceId): ?DeviceToken
    {
        $model = DeviceTokenModel::where('user_id', $userId->value)
            ->where('device_id', $deviceId)
            ->first();

        return $model ? DeviceTokenReflector::reverse($model) : null;
    }

    public function findByToken(string $token): ?DeviceToken
    {
        $model = DeviceTokenModel::where('token', $token)->first();
        return $model ? DeviceTokenReflector::reverse($model) : null;
    }

    public function revoke(DeviceTokenId $id): void
    {
        DeviceTokenModel::where('id', $id->value)->update(['is_active' => false]);
    }

    public function revokeAllForUser(UserId $userId): void
    {
        DeviceTokenModel::where('user_id', $userId->value)->update(['is_active' => false]);
    }

    public function revokeByToken(string $token): void
    {
        DeviceTokenModel::where('token', $token)->update(['is_active' => false]);
    }
}
