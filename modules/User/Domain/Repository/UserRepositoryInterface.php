<?php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function nextIdentity(): UserId;
    public function save(User $user): void;
    public function findByEmail(string $email): ?User;
    public function findByPhone(Phone $phone): ?User;
    public function findById(UserId $id): ?User;
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function all(array $filters = []): Collection;
    public function delete(UserId $id): void;
}
