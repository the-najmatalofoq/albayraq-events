<?php

namespace Modules\IAM\Domain\Service;

use Modules\User\Domain\ValueObject\UserId;

interface TokenManager
{
    public function createToken(string $userId): array;

    public function revokeAllTokens(string $userId): void;

    public function refresh(): array;

    public function invalidate(): void;

    public function getUserIdFromToken(): ?UserId;
}
