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
    public function findById(BankDetailId $uuid): ?BankDetail;
    public function nextIdentity(): BankDetailId;
}
