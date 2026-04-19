<?php
// filePath: modules/Question/Application/Queries/Crm/CrmGetQuestionQuery.php
declare(strict_types=1);

namespace Modules\Question\Application\Queries\Dashboard;

final readonly class GetQuestionQuery
{
    public function __construct(
        public string $id,
        public bool $withIdTrashed = false,
    ) {}
}
