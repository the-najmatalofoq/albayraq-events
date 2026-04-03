<?php
// modules/User/Application/Command/UpdateBankDetails/UpdateBankDetailsCommand.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateBankDetails;

final readonly class UpdateBankDetailsCommand
{
    public function __construct(
        public string $userId,
        public string $accountOwner,
        public string $bankName,
        public string $iban,
    ) {
    }
}
