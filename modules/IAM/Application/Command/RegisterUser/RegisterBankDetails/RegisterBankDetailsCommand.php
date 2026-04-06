<?php
// modules/IAM/Application/Command/RegisterUser/RegisterBankDetails/RegisterBankDetailsCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails;

use Modules\User\Domain\ValueObject\UserId;

final readonly class RegisterBankDetailsCommand
{
    public function __construct(
        public UserId $userId,
        public string $accountOwner,
        public string $bankName,
        public string $iban,
    ) {}
}
