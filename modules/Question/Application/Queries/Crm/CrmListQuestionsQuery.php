<?php
// filePath: modules/Question/Application/Queries/Crm/CrmListQuestionsQuery.php
declare(strict_types=1);

namespace Modules\Question\Application\Queries\Crm;

use Modules\Shared\Domain\ValueObject\FilterCriteria;

final readonly class CrmListQuestionsQuery
{
    public function __construct(
        public FilterCriteria $criteria,
    ) {}
}
