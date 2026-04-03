<?php
// modules/User/Application/Command/UpdateBankDetails/UpdateBankDetailsHandler.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateBankDetails;

use Modules\User\Domain\BankDetail;
use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final readonly class UpdateBankDetailsHandler
{
    public function __construct(
        private BankDetailRepositoryInterface $bankDetailRepository
    ) {}

    public function handle(UpdateBankDetailsCommand $command): void
    {
        $userId = UserId::fromString($command->userId);
        $bankDetail = $this->bankDetailRepository->findByUserId($userId);

        if ($bankDetail) {
            $bankDetail->updateDetails(
                accountOwner: $command->accountOwner,
                bankName: $command->bankName,
                iban: $command->iban,
                accountContact: null
            );
        } else {
            $bankDetail = BankDetail::create(
                uuid: $this->bankDetailRepository->nextIdentity(),
                userId: $userId,
                accountOwner: $command->accountOwner,
                bankName: $command->bankName,
                iban: $command->iban,
                accountContact: null
            );
        }

        $this->bankDetailRepository->save($bankDetail);
    }
}
