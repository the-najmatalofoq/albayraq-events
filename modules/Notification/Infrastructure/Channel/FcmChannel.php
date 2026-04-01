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

// notes:

/**
CodeRabbit
Missing exception handling for external FCM API call.

sendMulticast can throw exceptions on network failures, authentication issues, or FCM service errors. These should be caught to prevent notification delivery failures from crashing the calling process and to enable proper logging/monitoring.

+use Kreait\Firebase\Exception\MessagingException;
+use Psr\Log\LoggerInterface;
 public function __construct(
     private readonly DeviceTokenRepositoryInterface $tokenRepository,
+    private readonly LoggerInterface $logger,
 ) {}
-$messaging = Firebase::messaging();
-$report = $messaging->sendMulticast($message, $deviceTokens);
-
-$this->handleInvalidTokens($report);
+try {
+    $messaging = Firebase::messaging();
+    $report = $messaging->sendMulticast($message, $deviceTokens);
+    $this->handleInvalidTokens($report);
+} catch (MessagingException $e) {
+    $this->logger->error('FCM multicast failed', [
+        'user_id' => $userId->toString(),
+        'error' => $e->getMessage(),
+    ]);
+}
Kreait Firebase PHP SDK MessagingException sendMulticast
*/
