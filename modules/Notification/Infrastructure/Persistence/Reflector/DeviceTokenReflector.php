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

// code review:

/**
 * CodeRabbit
Add error handling for value object conversion.

The UserId::fromString() method may throw an exception for invalid or malformed input. Without error handling, exceptions will propagate unchecked, potentially causing unexpected failures. Consider wrapping this in a try-catch block to provide more context or handle conversion failures gracefully.

 public static function reverse(DeviceTokenModel $model): DeviceToken
 {
+    try {
+        $userId = UserId::fromString($model->user_id);
+    } catch (\Exception $e) {
+        throw new \RuntimeException(
+            "Failed to convert user_id to UserId: {$e->getMessage()}",
+            0,
+            $e
+        );
+    }
+
     return DeviceToken::register(
-        UserId::fromString($model->user_id),
+        $userId,
         $model->token,
         $model->platform,
         $model->device_name,
     );
 }
 */

 /**
  * CodeRabbit
Add null safety checks for model properties.

Accessing model properties without null checks could cause runtime errors if any of these fields are nullable in the database schema. Consider validating that user_id, token, platform, and device_name are non-null before using them, or ensure these fields are marked as non-nullable at the database level.

 public static function reverse(DeviceTokenModel $model): DeviceToken
 {
+    if ($model->user_id === null || $model->token === null ||
+        $model->platform === null || $model->device_name === null) {
+        throw new \InvalidArgumentException('DeviceTokenModel contains null values');
+    }
+
     return DeviceToken::register(
         UserId::fromString($model->user_id),
         $model->token,
         $model->platform,
         $model->device_name,
     );
 }
  */
