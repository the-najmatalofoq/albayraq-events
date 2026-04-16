<?php
// filePath: modules/Question/Application/Commands/Crm/CrmHardDeleteQuestionCommand.php
declare(strict_types=1);

namespace Modules\Question\Application\Commands\Dashboard;

final readonly class HardDeleteQuestionCommand
{
    public function __construct(
        public string $id,
    ) {}
}
