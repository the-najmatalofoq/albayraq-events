<?php

declare(strict_types=1);

namespace Modules\Quiz\Domain\Repository;

use Modules\Quiz\Domain\Quiz;
use Modules\Quiz\Domain\ValueObject\QuizId;

use Modules\Event\Domain\ValueObject\EventId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface QuizRepositoryInterface
{
    public function nextIdentity(): QuizId;

    public function findById(QuizId $id): ?Quiz;

    public function listByEventId(EventId $eventId): array;

    public function save(Quiz $quiz): void;

    public function delete(QuizId $id): void;
}
