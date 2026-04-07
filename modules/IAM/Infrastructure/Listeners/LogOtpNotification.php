<?php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\IAM\Domain\Event\OtpRequested;

final readonly class LogOtpNotification
{
    public function handle(OtpRequested $event): void
    {
        Log::info('OTP delivery (Logged):', [
            'user_id' => $event->userId->value,
            'code'    => $event->code,
            'purpose' => $event->purpose->value,
        ]);
    }
}
