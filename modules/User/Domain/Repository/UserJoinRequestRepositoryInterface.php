<?php

declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Domain\UserJoinRequest;
use Modules\User\Domain\ValueObject\UserJoinRequestId;
use Modules\User\Domain\ValueObject\UserId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface UserJoinRequestRepositoryInterface
{
    public function nextIdentity(): UserJoinRequestId;
    public function save(UserJoinRequest $joinRequest): void;
    public function findById(UserJoinRequestId $id): ?UserJoinRequest;
    public function findLatestByUserId(UserId $userId): ?UserJoinRequest;
    public function delete(UserJoinRequestId $id): void;

    /** @return UserJoinRequest[] */
    public function findAll(): array;

    public function paginate(int $page, int $perPage): LengthAwarePaginator;
}
