<?php

declare(strict_types=1);

namespace Modules\Question\Domain\Repository;

use Modules\Question\Domain\Question;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;

interface QuestionRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): QuestionId;

    public function findById(QuestionId $id): ?Question;

    public function findByIdWithTrashed(QuestionId $id): ?Question;

    public function save(Question $question): void;

    public function delete(QuestionId $id): void;

    public function hardDelete(QuestionId $id): void;

    public function restore(QuestionId $id): void;
}
