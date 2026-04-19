<?php

declare(strict_types=1);

namespace Modules\Notification\Application\Command\RegisterDeviceToken;

use Modules\User\Domain\ValueObject\UserId;

final readonly class RegisterDeviceTokenCommand
{
    public function __construct(
        public UserId $userId,
        public string $deviceId,
        public string $token,
        public string $platform,
        public ?string $deviceName = null,
    ) {}
}
