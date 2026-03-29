<?php

declare(strict_types=1);

namespace Modules\EmployeeQuizAttempt\Domain\Repository;

use Modules\EmployeeQuizAttempt\Domain\EmployeeQuizAttempt;
use Modules\EmployeeQuizAttempt\Domain\ValueObject\AttemptId;

interface EmployeeQuizAttemptRepositoryInterface
{
    public function findById(AttemptId $id): ?EmployeeQuizAttempt;

    public function save(EmployeeQuizAttempt $attempt): void;
}
