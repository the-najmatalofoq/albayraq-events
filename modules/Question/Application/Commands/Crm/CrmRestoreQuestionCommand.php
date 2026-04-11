<?php
// filePath: modules/Question/Application/Commands/Crm/CrmRestoreQuestionCommand.php
declare(strict_types=1);

namespace Modules\Question\Application\Commands\Crm;

final readonly class CrmRestoreQuestionCommand
{
    public function __construct(
        public string $id,
    ) {}
}
