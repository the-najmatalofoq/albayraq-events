<?php
// modules/Quiz/Application/Command/UpdateQuiz/UpdateQuizCommand.php
declare(strict_types=1);

namespace Modules\Quiz\Application\Command\UpdateQuiz;

final readonly class UpdateQuizCommand
{
    public function __construct(
        public string $id,
        public array $title,
        public ?array $description,
        public int $passingScore,
    ) {
    }
}
