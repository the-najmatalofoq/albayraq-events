<?php

declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\User\Domain\ValueObject\EmployeeNationalityId;

interface EmployeeNationalityRepositoryInterface
{
    public function nextIdentity(): EmployeeNationalityId;
}
