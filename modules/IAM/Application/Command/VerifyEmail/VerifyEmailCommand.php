<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Command\VerifyEmail;

final readonly class VerifyEmailCommand
{
    public function __construct(
        public string $userId,
        public string $code,
    ) {}
}
