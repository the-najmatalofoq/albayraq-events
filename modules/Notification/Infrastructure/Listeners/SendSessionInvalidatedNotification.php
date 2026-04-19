<?php

declare(strict_types=1);

namespace Modules\Notification\Infrastructure\Listeners;

use Modules\IAM\Domain\Event\UserLoggedIntoNewDevice;
use Modules\Notification\Application\Notification\SessionInvalidatedNotification;
use Modules\Notification\Domain\Repository\DeviceTokenRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

final class SendSessionInvalidatedNotification implements ShouldQueue
{
    public function __construct(
        private readonly DeviceTokenRepositoryInterface $deviceTokenRepository
    ) {}

    public function handle(UserLoggedIntoNewDevice $event): void
    {
        try {
            // 1. Notify the old devices via FCM that the session has been invalidated
            $event->user->notify(new SessionInvalidatedNotification(
                newDeviceName: $event->newDeviceName,
                locale: $event->locale
            ));

            // 2. Revoke all existing tokens for this user so they don't receive future notifications
            // Note: In production, you might want to delay this slightly to ensure the notification is dispatched
            $userId = UserId::fromString($event->user->id);
            $this->deviceTokenRepository->revokeAllForUser($userId);
        } catch (\Exception $e) {
            Log::error('Failed to process UserLoggedIntoNewDevice: ' . $e->getMessage(), [
                'user_id' => $event->user->id,
            ]);
        }
    }
}
