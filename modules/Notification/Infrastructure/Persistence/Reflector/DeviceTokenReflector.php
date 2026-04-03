<?php

declare(strict_types=1);

namespace Modules\Notification\Infrastructure\Persistence\Reflector;

use Modules\Notification\Domain\DeviceToken;
use Modules\Notification\Infrastructure\Persistence\Eloquent\DeviceTokenModel;
use Modules\User\Domain\ValueObject\UserId;

final class DeviceTokenReflector
{
    public static function reverse(DeviceTokenModel $model): DeviceToken
    {
        if ($model->user_id === null || $model->token === null ||
            $model->platform === null || $model->device_name === null) {
            throw new \InvalidArgumentException('DeviceTokenModel contains null values');
        }

        try {
            $userId = UserId::fromString($model->user_id);
        } catch (\Exception $e) {
            throw new \RuntimeException(
                "Failed to convert user_id to UserId: {$e->getMessage()}",
                0,
                $e
            );
        }

        return DeviceToken::register(
            $userId,
            $model->token,
            $model->platform,
            $model->device_name,
        );
    }
}


