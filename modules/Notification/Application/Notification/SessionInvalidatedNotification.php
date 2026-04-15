<?php

declare(strict_types=1);

namespace Modules\Notification\Application\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Notification\Infrastructure\Channel\FcmChannel;

final class SessionInvalidatedNotification extends Notification
{
    use Queueable;

    public function via(mixed $notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm(mixed $notifiable): array
    {
        return [
            'notification' => [
                'title' => __('messages.notifications.session_invalidated_title', [], 'ar'),
                'body' => __('messages.notifications.session_invalidated_body', [], 'ar'),
            ],
            'data' => [
                'type' => 'session_invalidated',
            ],
        ];
    }
}
