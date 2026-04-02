<?php

declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Services;

use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Infrastructure\Persistence\Eloquent\UserModel;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final readonly class JwtTokenManager implements TokenManager
{
    public function createToken(string $userId): array
    {
        $user = UserModel::findOrFail($userId);
        $accessTtl = (int) config('jwt.ttl');

        return [
            'access_token'  => $this->generateAccessToken($user, $accessTtl),
            'refresh_token' => $this->generateRefreshToken($user, $accessTtl),
            'expires_in'    => $accessTtl * 60,
            'token_type'    => 'Bearer',
        ];
    }

    private function generateAccessToken(UserModel $user, int $ttl): string
    {
        // نضبط الـ TTL في الـ Factory أولاً بسطر منفصل
        JWTAuth::factory()->setTTL($ttl);

        // نولد التوكن في سطر منفصل لضمان الحصول على string
        return JWTAuth::fromUser($user);
    }

    private function generateRefreshToken(UserModel $user): string
    {
        $ttl = (int) config('jwt.refresh_ttl');

        // نضبط الـ TTL لتوكن التجديد
        JWTAuth::factory()->setTTL($ttl);

        // نستخدم الـ claims ثم نولد التوكن
        return JWTAuth::claims(['type' => 'refresh'])->fromUser($user);
    }

    public function revokeAllTokens(string $userId): void
    {
        try {
            if ($token = JWTAuth::getToken()) {
                JWTAuth::invalidate($token);
            }
        } catch (\Exception $e) {
            // صامت
        }
    }
}
