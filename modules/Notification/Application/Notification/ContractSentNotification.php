<?php

declare(strict_types=1);

namespace Modules\Notification\Application\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Modules\Notification\Infrastructure\Channel\FcmChannel;

final class ContractSentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $eventName,
        private readonly string $contractId,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class, 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'contract_sent',
            'title' => ['ar' => 'عقد جديد', 'en' => 'New Contract'],
            'body' => ['ar' => "تم إرسال عقد لفعالية {$this->eventName}", 'en' => "Contract sent for {$this->eventName}"],
            'contract_id' => $this->contractId,
        ];
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        $locale = $notifiable->preferredLocale ?? 'en';
        $titles = ['ar' => 'عقد جديد', 'en' => 'New Contract'];
        $bodies = ['ar' => "تم إرسال عقد لفعالية {$this->eventName}", 'en' => "Contract sent for {$this->eventName}"];

        return CloudMessage::new()
            ->withNotification(FcmNotification::create($titles[$locale] ?? $titles['en'], $bodies[$locale] ?? $bodies['en']))
            ->withData(['contract_id' => $this->contractId, 'type' => 'contract_sent']);
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}

