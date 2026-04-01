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
        return DeviceToken::register(
            UserId::fromString($model->user_id),
            $model->token,
            $model->platform,
            $model->device_name,
        );
    }
}
