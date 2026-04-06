<?php
// modules/User/Domain/Repository/BankDetailRepositoryInterface.php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\User\Domain\BankDetail;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\BankDetailId;

interface BankDetailRepositoryInterface
{
    public function save(BankDetail $bankDetail): void;
    public function findByUserId(UserId $userId): ?BankDetail;
    public function nextIdentity(): BankDetailId;
    public function existsWithIban(string $iban): bool;
    /**
     * Update or create bank details for a user
     */
    public function updateOrCreate(
        UserId $userId,
        string $accountOwner,
        string $bankName,
        string $iban,
    ): BankDetail;
}
