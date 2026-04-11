<?php
// modules/ContractRejectionReason/Domain/Repository/ContractRejectionReasonRepositoryInterface.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Domain\Repository;

use Modules\ContractRejectionReason\Domain\ContractRejectionReason;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;
// fix: use the fiter in the listAll also.
interface ContractRejectionReasonRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): ContractRejectionReasonId;

    public function save(ContractRejectionReason $record): void;

    public function findById(ContractRejectionReasonId $id): ?ContractRejectionReason;

    public function listAll(): array;

    public function delete(ContractRejectionReasonId $id): void;
}
