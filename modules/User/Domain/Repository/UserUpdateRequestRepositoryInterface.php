<?php

declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\User\Domain\UserUpdateRequest;
use Modules\User\Domain\ValueObject\UserUpdateRequestId;

interface UserUpdateRequestRepositoryInterface
{
    public function nextIdentity(): UserUpdateRequestId;
    public function save(UserUpdateRequest $request): void;
    public function findById(string $id): ?UserUpdateRequest;
    /**
     * @return UserUpdateRequest[]
     */
    public function findByUserId(string $userId): array;
    /**
     * @return UserUpdateRequest[]
     */
    public function findAllPending(): array;
}
