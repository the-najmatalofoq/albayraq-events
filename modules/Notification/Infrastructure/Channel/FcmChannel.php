<?php

declare(strict_types=1);

namespace Modules\Notification\Infrastructure\Channel;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Modules\Notification\Domain\Repository\DeviceTokenRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final class FcmChannel
{
    public function __construct(
        private readonly DeviceTokenRepositoryInterface $tokenRepository,
    ) {}

    public function send(object $notifiable, Notification $notification): void
    {
        $userId = UserId::fromString($notifiable->id);
        $tokens = $this->tokenRepository->findByUser($userId);

        if (empty($tokens)) {
            return;
        }

        $deviceTokens = array_map(fn($t) => $t->getToken(), $tokens);

        if (!method_exists($notification, 'toFcm')) {
            return;
        }

        $message = $notification->toFcm($notifiable);

        if (!$message instanceof CloudMessage) {
            return;
        }

        $messaging = Firebase::messaging();
        $report = $messaging->sendMulticast($message, $deviceTokens);

        $this->handleInvalidTokens($report);
    }

    private function handleInvalidTokens(MulticastSendReport $report): void
    {
        foreach ($report->failures()->getItems() as $failure) {
            $errorMessage = $failure->error()->getMessage();
            if (in_array($errorMessage, ['NotRegistered', 'InvalidRegistration'])) {
                $this->tokenRepository->revokeByToken($failure->target()->value());
            }
        }
    }
}
