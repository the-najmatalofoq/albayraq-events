<?php
// filePath: modules/Question/Application/Commands/Crm/CrmUpdateQuestionCommand.php
declare(strict_types=1);

namespace Modules\Question\Application\Commands\Dashboard;

final readonly class UpdateQuestionCommand
{
    public function __construct(
        public string $id,
        public string $quizId,
        public array $content,
        public array $options,
        public string $type,
        public int $scoreWeight,
    ) {}
}
