<?php
// modules/IAM/Application/Command/RegisterUser/RegisterBankDetails/RegisterBankDetailsHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails;

use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\IAM\Domain\Exception\BankAlreadyExistsException;

final readonly class RegisterBankDetailsHandler
{
    public function __construct(
        private BankDetailRepositoryInterface $bankDetailRepository
    ) {}

    public function handle(RegisterBankDetailsCommand $command): void
    {
        if ($this->bankDetailRepository->existsWithIban($command->iban)) {
            throw BankAlreadyExistsException::withIban();
        }

        $this->bankDetailRepository->updateOrCreate(
            userId: $command->userId,
            accountOwner: $command->accountOwner,
            bankName: $command->bankName,
            iban: $command->iban
        );
    }
}
