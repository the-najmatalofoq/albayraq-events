<?php
// modules/ViolationType/Application/Query/ListViolationTypes/ListViolationTypesHandler.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Query\ListViolationTypes;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;

final readonly class ListViolationTypesHandler
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository
    ) {}

    public function handle(ListViolationTypesQuery $query): array
    {
        return $this->repository->paginate(
            $query->pagination,
            $query->search
        );
    }
}
