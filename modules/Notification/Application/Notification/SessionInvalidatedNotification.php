<?php

declare(strict_types=1);

namespace Modules\Notification\Application\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Modules\Notification\Infrastructure\Channel\FcmChannel;

final class SessionInvalidatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $newDeviceName,
        private readonly string $locale = 'ar',
    ) {}

    public function via(object $notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        return CloudMessage::new()
            ->withNotification(FcmNotification::create(
                $this->getTitle(),
                $this->getBody()
            ))
            ->withData([
                'type' => 'session_invalidated',
                'new_device_name' => $this->newDeviceName,
                'action' => 'force_logout',
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'session_invalidated',
            'title' => $this->getTitle(),
            'body' => $this->getBody(),
            'new_device_name' => $this->newDeviceName,
        ];
    }

    private function getTitle(): string
    {
        return $this->locale === 'ar' 
            ? 'تم تسجيل الخروج' 
            : 'Logged Out';
    }

    private function getBody(): string
    {
        return $this->locale === 'ar'
            ? "تم تسجيل الدخول من جهاز جديد ({$this->newDeviceName})، تم تسجيل خروجك من هذا الجهاز"
            : "Logged in from new device ({$this->newDeviceName}), you have been logged out";
    }
}