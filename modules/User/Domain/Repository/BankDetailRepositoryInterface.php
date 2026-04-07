<?php
// modules/User/Domain/Repository/BankDetailRepositoryInterface.php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\User\Domain\BankDetail;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\BankDetailId;

interface BankDetailRepositoryInterface
{
    public function save(BankDetail $bankDetail): void;
    public function findByUserId(UserId $userId): ?BankDetail;
    public function findById(BankDetailId $id): ?BankDetail;
    public function nextIdentity(): BankDetailId;
    public function existsWithIban(string $iban): bool;
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function all(array $filters = []): Collection;
    public function updateOrCreate(
        UserId $userId,
        string $accountOwner,
        string $bankName,
        string $iban,
    ): BankDetail;
}
