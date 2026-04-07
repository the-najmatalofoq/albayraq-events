<?php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Services;

use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Domain\ValueObject\UserId;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final readonly class JwtTokenManager implements TokenManager
{
    public function generateToken(mixed $subject): string
    {
        return JWTAuth::fromUser($subject);
    }

    public function refreshToken(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    public function invalidateToken(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function getUserIdFromToken(): ?UserId
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user === null) {
                return null;
            }

            return UserId::fromString($user->getAuthIdentifier());
        } catch (\Throwable) {
            return null;
        }
    }
}
