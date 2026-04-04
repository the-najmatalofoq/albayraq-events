<?php
// modules/IAM/Application/Command/RegisterUser/RegisterBankDetails/RegisterBankDetailsHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails;

use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final readonly class RegisterBankDetailsHandler
{
    public function __construct(
        private BankDetailRepositoryInterface $bankDetailRepository
    ) {
    }

    public function handle(RegisterBankDetailsCommand $command): void
    {
        $userId = UserId::fromString($command->userId);

        $this->bankDetailRepository->updateOrCreate(
            userId: $userId,
            accountOwner: $command->accountOwner,
            bankName: $command->bankName,
            iban: $command->iban,
            accountContact: null
        );
    }
}
