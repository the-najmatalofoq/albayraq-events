<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\ResetPassword;

final readonly class ResetPasswordCommand
{
    public function __construct(
        public string $email,
        public string $code,
        public string $password,
    ) {
    }
}
