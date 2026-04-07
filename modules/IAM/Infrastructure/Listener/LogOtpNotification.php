<?php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Listener;

use Illuminate\Support\Facades\Log;
use Modules\IAM\Domain\Event\OtpRequested;

final class LogOtpNotification
{
    public function handle(OtpRequested $event): void
    {
        Log::info('OTP Requested', [
            'userId' => $event->userId->value,
            'otpCode' => $event->code,
            'purpose' => $event->purpose->value,
        ]);
        
        // FUTURE: Dispatch SMS or Email here.
    }
}
