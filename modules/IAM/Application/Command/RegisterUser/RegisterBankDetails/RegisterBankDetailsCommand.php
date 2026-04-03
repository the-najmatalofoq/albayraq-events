<?php
// modules/IAM/Application/Command/RegisterUser/RegisterBankDetails/RegisterBankDetailsCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails;

final readonly class RegisterBankDetailsCommand
{
    public function __construct(
        public string $userId,
        public string $accountOwner,
        public string $bankName,
        public string $iban,
    ) {}
}
