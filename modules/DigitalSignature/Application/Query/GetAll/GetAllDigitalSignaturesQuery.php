<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Query\GetAll;

use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Domain\ValueObject\SortCriteria;

final readonly class GetAllDigitalSignaturesQuery
{
    public function __construct(
        public ?FilterCriteria $filters = null,
        public ?SortCriteria $sort = null,
    ) {}
}
