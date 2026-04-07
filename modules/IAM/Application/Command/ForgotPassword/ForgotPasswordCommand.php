<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\ForgotPassword;

final readonly class ForgotPasswordCommand
{
    public function __construct(
        public string $email,
    ) {
    }
}
