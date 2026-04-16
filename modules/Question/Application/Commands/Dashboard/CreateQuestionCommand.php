<?php
// filePath: modules/Question/Application/Commands/Crm/CrmCreateQuestionCommand.php
declare(strict_types=1);

namespace Modules\Question\Application\Commands\Dashboard;

final readonly class CreateQuestionCommand
{
    public function __construct(
        public string $quizId,
        public array $content,
        public array $options,
        public string $type = 'multiple_choice',
        public int $scoreWeight = 1,
    ) {}
}
