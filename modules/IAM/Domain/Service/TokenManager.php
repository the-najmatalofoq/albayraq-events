<?php
declare(strict_types=1);

namespace Modules\IAM\Domain\Service;

use Modules\User\Domain\ValueObject\UserId;

interface TokenManager
{
    public function generateToken(mixed $subject): string;
    public function refreshToken(): string;
    public function invalidateToken(): void;
    public function getUserIdFromToken(): ?UserId;
}
