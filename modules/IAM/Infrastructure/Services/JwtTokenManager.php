<?php

declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Services;

use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

// fix: we have two files are JwtTokenManager, keep the one in the IAM and in the Service folder but merge the two to make them powerfull
final readonly class JwtTokenManager implements TokenManager
{
    public function createToken(string $userId): array
    {
        $user = UserModel::findOrFail($userId);
        $accessTtl = (int) config('jwt.ttl');

        return [
            'access_token' => $this->generateAccessToken($user, $accessTtl),
            // fix: fix the Too many arguments to function generateRefreshToken(). 2 provided, but 1 accepted.PHP(PHP0443)
            'refresh_token' => $this->generateRefreshToken($user, $accessTtl),
            'expires_in' => $accessTtl * 60,
            'token_type' => 'Bearer',
        ];
    }

    private function generateAccessToken(UserModel $user, int $ttl): string
    {
        JWTAuth::factory()->setTTL($ttl);

        return JWTAuth::fromUser($user);
    }

    private function generateRefreshToken(UserModel $user): string
    {
        $ttl = (int) config('jwt.refresh_ttl');

        JWTAuth::factory()->setTTL($ttl);

        return JWTAuth::claims(['type' => 'refresh'])->fromUser($user);
    }

    public function revokeAllTokens(string $userId): void
    {
        try {
            if ($token = JWTAuth::getToken()) {
                JWTAuth::invalidate($token);
            }
        } catch (\Exception $e) {
        }
    }
}
