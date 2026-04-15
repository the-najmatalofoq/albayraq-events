<?php

declare(strict_types=1);

namespace Modules\Notification\Infrastructure\Persistence\Reflector;

use Modules\Notification\Domain\DeviceToken;
use Modules\Notification\Domain\ValueObject\DeviceTokenId;
use Modules\Notification\Infrastructure\Persistence\Eloquent\DeviceTokenModel;
use Modules\User\Domain\ValueObject\UserId;

final class DeviceTokenReflector
{
    public static function reverse(DeviceTokenModel $model): DeviceToken
    {
        if (
            $model->id === null || $model->user_id === null || $model->device_id === null ||
            $model->token === null || $model->platform === null
        ) {
            throw new \InvalidArgumentException('DeviceTokenModel contains null values');
        }

        try {
            $userId = UserId::fromString($model->user_id);
            $id = new DeviceTokenId($model->id);
        } catch (\Exception $e) {
            throw new \RuntimeException(
                "Failed to reconstitute DeviceTokenId or UserId: {$e->getMessage()}",
                0,
                $e
            );
        }

        return DeviceToken::hydrate(
            $id,
            $userId,
            $model->device_id,
            $model->token,
            $model->platform,
            $model->device_name,
            $model->is_active,
            $model->last_used_at?->toDateTimeImmutable()
        );
    }
}
