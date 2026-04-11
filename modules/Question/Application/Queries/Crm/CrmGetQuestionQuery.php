<?php
// filePath: modules/Question/Application/Queries/Crm/CrmGetQuestionQuery.php
declare(strict_types=1);

namespace Modules\Question\Application\Queries\Crm;

final readonly class CrmGetQuestionQuery
{
    public function __construct(
        public string $id,
        public bool $withIdTrashed = false,
    ) {}
}
