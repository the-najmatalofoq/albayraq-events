<?php
// filePath: modules/Question/Application/Queries/Crm/CrmListPaginatedQuestionsQuery.php
declare(strict_types=1);

namespace Modules\Question\Application\Queries\Dashboard;

use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;

final readonly class ListPaginatedQuestionsQuery
{
    public function __construct(
        public FilterCriteria $criteria,
        public PaginationCriteria $pagination,
    ) {}
}
