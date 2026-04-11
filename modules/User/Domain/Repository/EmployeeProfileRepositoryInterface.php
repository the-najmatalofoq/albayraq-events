<?php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;
use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\EmployeeProfileId;
// fix: use the fiter in the listAll also.

interface EmployeeProfileRepositoryInterface extends FilterableRepositoryInterface
{
    public function save(EmployeeProfile $profile): void;
    public function findByUserId(UserId $userId): ?EmployeeProfile;
    public function findById(EmployeeProfileId $uuid): ?EmployeeProfile;
    public function nextIdentity(): EmployeeProfileId;
    public function listAll(): array;
    public function delete(EmployeeProfileId $id): void;
}
