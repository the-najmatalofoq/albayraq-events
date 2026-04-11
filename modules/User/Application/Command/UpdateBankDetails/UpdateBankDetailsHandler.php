<?php
// modules/User/Application/Command/UpdateBankDetails/UpdateBankDetailsHandler.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateBankDetails;

use Modules\User\Domain\BankDetail;
use Modules\User\Domain\Repository\BankDetailRepositoryInterface;

final readonly class UpdateBankDetailsHandler
{
    public function __construct(
        private BankDetailRepositoryInterface $bankDetailRepository
    ) {}

    public function handle(UpdateBankDetailsCommand $command): void
    {
        $bankDetail = $this->bankDetailRepository->findByUserId($command->userId);

        if ($bankDetail) {
            $bankDetail->updateDetails(
                accountOwner: $command->accountOwner,
                bankName: $command->bankName,
                iban: $command->iban,
            );
        } else {
            $bankDetail = BankDetail::create(
                uuid: $this->bankDetailRepository->nextIdentity(),
                userId: $command->userId,
                accountOwner: $command->accountOwner,
                bankName: $command->bankName,
                iban: $command->iban,
            );
        }

        $this->bankDetailRepository->save($bankDetail);
    }
}
