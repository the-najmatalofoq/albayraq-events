<?php
// modules/IAM/Application/Command/RegisterUser/RegisterBankDetalis/RegisterBankDetailsHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails;

use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\User\Domain\BankDetail;
use Modules\User\Domain\ValueObject\UserId;

final readonly class RegisterBankDetailsHandler
{
    public function __construct(
        private BankDetailRepositoryInterface $bankRepository,
    ) {}

    public function handle(RegisterBankDetailsCommand $command, UserId $userId): void
    {
        $bankId = $this->bankRepository->nextIdentity();
        $bankDetail = BankDetail::create(
            uuid: $bankId,
            userId: $userId,
            accountOwner: $command->accountOwner,
            bankName: $command->bankName,
            iban: $command->iban
        );
        $this->bankRepository->save($bankDetail);
    }
}
