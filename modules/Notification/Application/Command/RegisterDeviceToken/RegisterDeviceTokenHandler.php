<?php

declare(strict_types=1);

namespace Modules\Notification\Application\Command\RegisterDeviceToken;

use Modules\Notification\Domain\DeviceToken;
use Modules\Notification\Domain\Repository\DeviceTokenRepositoryInterface;
use Modules\Notification\Domain\ValueObject\DeviceTokenId;

final readonly class RegisterDeviceTokenHandler
{
    public function __construct(
        private DeviceTokenRepositoryInterface $repository
    ) {}

    public function handle(RegisterDeviceTokenCommand $command): DeviceTokenId
    {
        // Check if a token record already exists for this User + Device
        $existingRecord = $this->repository->findByUserAndDevice($command->userId, $command->deviceId);

        if ($existingRecord) {
            // Update the token string (it might have changed) and mark as active
            $existingRecord->updateToken($command->token);
            $existingRecord->markUsed();
            $this->repository->save($existingRecord);
            return $existingRecord->getId();
        }

        // Register a new device token
        $deviceToken = DeviceToken::register(
            userId: $command->userId,
            deviceId: $command->deviceId,
            token: $command->token,
            platform: $command->platform,
            deviceName: $command->deviceName
        );

        $this->repository->save($deviceToken);

        return $deviceToken->getId();
    }
}
