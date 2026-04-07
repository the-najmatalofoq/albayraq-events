<?php
declare(strict_types=1);

namespace Modules\IAM\Domain\Enum;

enum OtpPurposeEnum: string
{
    case EMAIL_VERIFICATION = 'email_verification';
    case PASSWORD_RESET = 'password_reset';
}
