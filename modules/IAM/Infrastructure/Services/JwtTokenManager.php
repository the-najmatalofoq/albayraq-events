<?php

declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Services;

use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
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

    public function refresh(): array
    {
        $newToken = JWTAuth::refresh();
        $accessTtl = (int) config('jwt.ttl');
        
        /** @var UserModel|null $user */
        $user = JWTAuth::user();

        return [
            'access_token'  => $newToken,
            'refresh_token' => $user ? $this->generateRefreshToken($user) : '',
            'expires_in'    => $accessTtl * 60,
            'token_type'    => 'Bearer',
        ];
    }

    public function invalidate(): void
    {
        try {
            if ($token = JWTAuth::getToken()) {
                JWTAuth::invalidate($token);
            }
        } catch (\Exception $e) {
          
        }
    }
}
