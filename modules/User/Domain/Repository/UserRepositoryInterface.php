<?php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;

interface UserRepositoryInterface extends FilterableRepositoryInterface
{
    public function save(User $user, ?string $avatarPath = null): void;
    public function findByEmail(string $email): ?User;
    public function findByPhone(Phone $phone): ?User;
    public function findById(UserId $id): ?User;
    public function delete(UserId $id): void;
}
