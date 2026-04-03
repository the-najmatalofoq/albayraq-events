<?php

declare(strict_types=1);

namespace Modules\Notification\Application\Listeners;

use Illuminate\Support\Facades\Notification;
use Modules\EventContract\Domain\Events\ContractSent;
use Modules\Notification\Application\Notification\ContractSentNotification;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;

final readonly class SendContractSentNotification
{
    public function handle(ContractSent $event): void
    {
        $user = UserModel::find($event->userId);

        if ($user) {
            Notification::send($user, new ContractSentNotification(
                $event->eventName,
                $event->contractId
            ));
        }
    }
}
