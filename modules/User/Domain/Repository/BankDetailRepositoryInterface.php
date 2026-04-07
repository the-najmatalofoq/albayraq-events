<?php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;
use Modules\User\Domain\BankDetail;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\BankDetailId;

interface BankDetailRepositoryInterface extends FilterableRepositoryInterface
{
    public function save(BankDetail $bankDetail): void;
    public function findByUserId(UserId $userId): ?BankDetail;
    public function findById(BankDetailId $id): ?BankDetail;
    public function nextIdentity(): BankDetailId;
    public function existsWithIban(string $iban): bool;
    public function updateOrCreate(
        UserId $userId,
        string $accountOwner,
        string $bankName,
        string $iban,
    ): BankDetail;
}
