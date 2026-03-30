<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Query\GetAllPaginated;

use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;

final readonly class GetAllDigitalSignaturesPaginatedHandler
{
    public function __construct(
        private DigitalSignatureRepositoryInterface $repository,
    ) {}

    public function handle(GetAllDigitalSignaturesPaginatedQuery $query): array
    {
        return $this->repository->findAllPaginated(
            page: $query->page,
            perPage: $query->perPage,
            filters: $query->filters,
            orderBy: $query->orderBy,
            orderDirection: $query->orderDirection,
        );
    }
}
