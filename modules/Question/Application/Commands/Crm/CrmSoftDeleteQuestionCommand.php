<?php
// filePath: modules/Question/Application/Commands/Crm/CrmSoftDeleteQuestionCommand.php
declare(strict_types=1);

namespace Modules\Question\Application\Commands\Crm;

final readonly class CrmSoftDeleteQuestionCommand
{
    public function __construct(
        public string $id,
    ) {}
}
