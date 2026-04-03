<?php

declare(strict_types=1);

namespace Modules\Notification\Infrastructure\Channel;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Exception\MessagingException;
use Psr\Log\LoggerInterface;
use Modules\Notification\Domain\Repository\DeviceTokenRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final class FcmChannel
{
    public function __construct(
        private readonly DeviceTokenRepositoryInterface $tokenRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

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

        try {
            $messaging = Firebase::messaging();
            $report = $messaging->sendMulticast($message, $deviceTokens);
            $this->handleInvalidTokens($report);
        } catch (MessagingException $e) {
            $this->logger->error('FCM multicast failed', [
                'user_id' => $userId->value,
                'error' => $e->getMessage(),
            ]);
        }
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