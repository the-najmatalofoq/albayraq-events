<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\SendEmailVerification;

final readonly class SendEmailVerificationCommand
{
    public function __construct(
        public string $userId,
    ) {
    }
}
