<?php

declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\User\Domain\ValueObject\EmployeeNationalityId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface EmployeeNationalityRepositoryInterface
{
    public function nextIdentity(): EmployeeNationalityId;
}
