<?php

declare(strict_types=1);

namespace Modules\Notification\Application\Command\RevokeDeviceToken;

final readonly class RevokeDeviceTokenCommand
{
    public function __construct(
        public string $token,
    ) {}
}
