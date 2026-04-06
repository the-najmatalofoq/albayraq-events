<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Service;

interface TokenManager
{
    public function createToken(string $userId): array;

    public function revokeAllTokens(string $userId): void;

    public function refresh(): array;

    public function invalidate(): void;
}
