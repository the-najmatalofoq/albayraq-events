<?php
// modules/User/Domain/Repository/UserProfileRepositoryInterface.php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\User\Domain\UserProfile;
use Modules\User\Domain\ValueObject\UserProfileId;
use Modules\IAM\Domain\ValueObject\UserId;

interface UserProfileRepositoryInterface
{
    public function nextIdentity(): UserProfileId;

    public function save(UserProfile $userProfile): void;

    public function findById(UserProfileId $id): ?UserProfile;

    public function findByUserId(UserId $userId): ?UserProfile;

    public function findByEmployeeNumber(string $employeeNumber): ?UserProfile;

    public function listAll(): array;
}
