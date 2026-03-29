<?php

declare(strict_types=1);

namespace Modules\Quiz\Domain\Repository;

use Modules\Quiz\Domain\Quiz;
use Modules\Quiz\Domain\ValueObject\QuizId;

interface QuizRepositoryInterface
{
    public function findById(QuizId $id): ?Quiz;

    public function save(Quiz $quiz): void;
}
