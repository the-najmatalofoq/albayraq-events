<?php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;
use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\EmployeeProfileId;

interface EmployeeProfileRepositoryInterface extends FilterableRepositoryInterface
{
    public function save(EmployeeProfile $profile): void;
    public function findByUserId(UserId $userId): ?EmployeeProfile;
    public function findById(EmployeeProfileId $uuid): ?EmployeeProfile;
    public function nextIdentity(): EmployeeProfileId;
    public function delete(EmployeeProfileId $id): void;
}
