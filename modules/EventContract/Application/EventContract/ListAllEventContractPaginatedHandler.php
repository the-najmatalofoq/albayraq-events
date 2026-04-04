<?php

declare(strict_types=1);

namespace Modules\EventContract\Application\EventContract;

use Modules\Shared\Application\Query\Pagination;
use Modules\Shared\Domain\PaginatedResult;
use Modules\EventContract\Domain\Repository\EventContractRepositoryInterface;

final readonly class ListAllEventContractPaginatedHandler
{
    public function __construct(
        private EventContractRepositoryInterface $repository,
    ) {}

    public function handle(Pagination $query): PaginatedResult
    {
        return $this->repository->paginate(
            page: $query->page,
            perPage: $query->perPage
        );
    }
}
