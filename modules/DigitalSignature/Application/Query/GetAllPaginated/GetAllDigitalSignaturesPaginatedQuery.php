<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Query\GetAllPaginated;

use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
use Modules\Shared\Domain\ValueObject\SortCriteria;

final readonly class GetAllDigitalSignaturesPaginatedQuery
{
    public function __construct(
        public PaginationCriteria $pagination,
        public ?FilterCriteria $filters = null,
        public ?SortCriteria $sort = null,
    ) {}
}
