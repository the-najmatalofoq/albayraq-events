<?php
// modules/ContractRejectionReason/Domain/Repository/ContractRejectionReasonRepositoryInterface.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Domain\Repository;

use Modules\ContractRejectionReason\Domain\ContractRejectionReason;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContractRejectionReasonRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): ContractRejectionReasonId;

    public function save(ContractRejectionReason $record): void;

    public function findById(ContractRejectionReasonId $id): ?ContractRejectionReason;

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator;

    public function all(FilterCriteria $criteria): Collection;

    public function delete(ContractRejectionReasonId $id): void;
}
