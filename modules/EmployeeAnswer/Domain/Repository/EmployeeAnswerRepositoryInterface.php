<?php

declare(strict_types=1);

namespace Modules\EmployeeAnswer\Domain\Repository;

use Modules\EmployeeAnswer\Domain\EmployeeAnswer;
use Modules\EmployeeAnswer\Domain\ValueObject\AnswerId;

interface EmployeeAnswerRepositoryInterface
{
    public function findById(AnswerId $id): ?EmployeeAnswer;

    public function save(EmployeeAnswer $answer): void;
}
