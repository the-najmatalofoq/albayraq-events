<?php
// modules/User/Application/Command/UpdateBankDetails/UpdateBankDetailsCommand.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateBankDetails;

use Modules\User\Domain\ValueObject\UserId;

final readonly class UpdateBankDetailsCommand
{
    public function __construct(
        public UserId $userId,
        public string $accountOwner,
        public string $bankName,
        public string $iban,
    ) {
    }
}
