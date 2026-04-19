<?php

declare(strict_types=1);

namespace Modules\Notification\Domain\Repository;

use Modules\Notification\Domain\DeviceToken;
use Modules\Notification\Domain\ValueObject\DeviceTokenId;
use Modules\User\Domain\ValueObject\UserId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface DeviceTokenRepositoryInterface
{
    public function save(DeviceToken $token): void;
    public function findById(DeviceTokenId $id): ?DeviceToken;
    public function findByUser(UserId $userId): array;
    public function findByUserAndDevice(UserId $userId, string $deviceId): ?DeviceToken;
    public function findByToken(string $token): ?DeviceToken;
    public function revoke(DeviceTokenId $id): void;
    public function revokeAllForUser(UserId $userId): void;
    public function revokeByToken(string $token): void;
}
