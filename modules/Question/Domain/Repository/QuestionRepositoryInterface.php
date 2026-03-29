<?php

declare(strict_types=1);

namespace Modules\Question\Domain\Repository;

use Modules\Question\Domain\Question;
use Modules\Question\Domain\ValueObject\QuestionId;

interface QuestionRepositoryInterface
{
    public function findById(QuestionId $id): ?Question;

    public function save(Question $question): void;
}
