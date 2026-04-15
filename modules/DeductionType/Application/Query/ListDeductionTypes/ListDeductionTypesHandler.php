<?php
// modules/DeductionType/Application/Query/ListDeductionTypes/ListDeductionTypesHandler.php
declare(strict_types=1);

namespace Modules\DeductionType\Application\Query\ListDeductionTypes;

use Modules\DeductionType\Domain\Repository\DeductionTypeRepositoryInterface;

final readonly class ListDeductionTypesHandler
{
    public function __construct(
        private DeductionTypeRepositoryInterface $repository
    ) {}

    public function handle(ListDeductionTypesQuery $query): mixed
    {
        if ($query->paginated) {
            return $this->repository->paginate($query->criteria, $query->perPage);
        }

        return $this->repository->all($query->criteria);
    }
}
