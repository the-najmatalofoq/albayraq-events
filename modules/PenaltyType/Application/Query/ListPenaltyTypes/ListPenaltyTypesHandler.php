<?php
// modules/PenaltyType/Application/Query/ListPenaltyTypes/ListPenaltyTypesHandler.php
declare(strict_types=1);

namespace Modules\PenaltyType\Application\Query\ListPenaltyTypes;

use Modules\PenaltyType\Domain\Repository\PenaltyTypeRepositoryInterface;

final readonly class ListPenaltyTypesHandler
{
    public function __construct(
        private PenaltyTypeRepositoryInterface $repository
    ) {}

    public function handle(ListPenaltyTypesQuery $query): mixed
    {
        if ($query->paginated) {
            return $this->repository->paginate($query->criteria, $query->perPage);
        }

        return $this->repository->all($query->criteria);
    }
}
