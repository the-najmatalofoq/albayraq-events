<?php

declare(strict_types=1);

namespace Modules\Question\Domain\Repository;

use Modules\Question\Domain\Question;
use Modules\Question\Domain\ValueObject\QuestionId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface QuestionRepositoryInterface
{
    public function findById(QuestionId $id): ?Question;

    public function save(Question $question): void;
}
