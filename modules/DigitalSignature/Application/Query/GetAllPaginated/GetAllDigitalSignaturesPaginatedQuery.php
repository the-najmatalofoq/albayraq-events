<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Query\GetAllPaginated;

final readonly class GetAllDigitalSignaturesPaginatedQuery
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 15,
        public array $filters = [],
        public string $orderBy = 'signed_at',
        public string $orderDirection = 'desc',
    ) {}
}
