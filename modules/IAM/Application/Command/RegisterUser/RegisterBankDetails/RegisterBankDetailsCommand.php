<?php
// modules/IAM/Application/Command/RegisterUser/DTOs/RegisterBankDetails.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails;

final readonly class RegisterBankDetailsCommand
{
    public function __construct(
        public string $iban,
        public string $bankName,
        public string $accountOwner,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            iban: $data['iban'],
            bankName: $data['bank_name'],
            accountOwner: $data['account_owner'],
        );
    }
}
