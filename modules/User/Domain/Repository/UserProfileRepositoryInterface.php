<?php
// modules/User/Domain/Repository/UserProfileRepositoryInterface.php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\ValueObject\UserProfileId;
use Modules\User\Domain\ValueObject\UserId;

interface UserProfileRepositoryInterface
{
    public function nextIdentity(): UserProfileId;

    public function save(EmployeeProfile $userProfile): void;

    public function findById(UserProfileId $id): ?EmployeeProfile;

    public function findByUserId(UserId $userId): ?EmployeeProfile;

    public function findByEmployeeNumber(string $employeeNumber): ?EmployeeProfile;

    public function listAll(): array;
}
