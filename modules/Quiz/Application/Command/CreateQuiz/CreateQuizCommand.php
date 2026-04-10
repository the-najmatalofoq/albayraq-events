<?php
// modules/Quiz/Application/Command/CreateQuiz/CreateQuizCommand.php
declare(strict_types=1);

namespace Modules\Quiz\Application\Command\CreateQuiz;

final readonly class CreateQuizCommand
{
    public function __construct(
        public string $eventId,
        public array $title,
        public ?array $description,
        public int $passingScore,
    ) {
    }
}
