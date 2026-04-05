<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\User\Domain\Repository\EmployeeNationalityRepositoryInterface;
use Modules\User\Domain\ValueObject\EmployeeNationalityId;

final class EloquentEmployeeNationalityRepository implements EmployeeNationalityRepositoryInterface
{
    public function nextIdentity(): EmployeeNationalityId
    {
        return EmployeeNationalityId::generate();
    }
}
