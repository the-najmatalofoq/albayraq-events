<?php

declare(strict_types=1);

namespace Modules\Notification\Application\Command\RevokeDeviceToken;

use Modules\Notification\Domain\Repository\DeviceTokenRepositoryInterface;

final readonly class RevokeDeviceTokenHandler
{
    public function __construct(
        private DeviceTokenRepositoryInterface $repository
    ) {}

    public function handle(RevokeDeviceTokenCommand $command): void
    {
        $this->repository->revokeByToken($command->token);
    }
}
