<?php
// modules/ContractRejectionReason/Domain/Repository/ContractRejectionReasonRepositoryInterface.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Domain\Repository;

use Modules\ContractRejectionReason\Domain\ContractRejectionReason;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;

interface ContractRejectionReasonRepositoryInterface
{
    public function nextIdentity(): ContractRejectionReasonId;

    public function save(ContractRejectionReason $reason): void;

    public function findById(ContractRejectionReasonId $id): ?ContractRejectionReason;

    public function listAll(): array;
}
