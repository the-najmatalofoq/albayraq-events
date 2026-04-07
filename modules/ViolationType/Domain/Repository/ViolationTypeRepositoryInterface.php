<?php
// modules/ViolationType/Domain/Repository/ViolationTypeRepositoryInterface.php
declare(strict_types=1);

namespace Modules\ViolationType\Domain\Repository;

use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ViolationTypeRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): ViolationTypeId;

    public function save(ViolationType $violationType): void;

    public function findById(ViolationTypeId $id): ?ViolationType;

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator;

    public function all(FilterCriteria $criteria): Collection;

    public function delete(ViolationTypeId $id): void;
}
