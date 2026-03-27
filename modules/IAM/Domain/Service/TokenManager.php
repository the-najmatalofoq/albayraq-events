<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Service;

interface TokenManager
{
    public function createToken(string $userId): string;

    public function revokeAllTokens(string $userId): void;
}
