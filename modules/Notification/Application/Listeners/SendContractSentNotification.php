<?php

declare(strict_types=1);

namespace Modules\Notification\Application\Listeners;

use Modules\EventContract\Domain\Events\ContractSent;
use Modules\Notification\Application\Notification\ContractSentNotification;
use Modules\User\Infrastructure\Persistence\Eloquent\UserModel;

final class SendContractSentNotification
{
    public function handle(ContractSent $event): void
    {
        $user = UserModel::find($event->userId->toString());

        if ($user) {
            $user->notify(new ContractSentNotification(
                $event->eventName,
                $event->contractId->toString(),
            ));
        }
    }
}
