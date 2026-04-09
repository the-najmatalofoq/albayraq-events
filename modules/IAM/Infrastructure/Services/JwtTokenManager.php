<?php

declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Services;

use Modules\IAM\Domain\Service\TokenManager;
use Modules\IAM\Domain\Exception\TokenExpiredException;
use Modules\User\Domain\ValueObject\UserId;
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
        try {
            $oldToken = JWTAuth::getToken();

            $payload = JWTAuth::getPayload($oldToken);

            $userId = $payload->get('sub');

            $newToken = JWTAuth::refresh($oldToken);

            $user = UserModel::find($userId);

            if (!$user) {
                throw new \Exception("User ID [{$userId}] not found in database.");
            }

            $accessTtl = (int) config('jwt.ttl');

            return [
                'access_token'  => $newToken,
                'refresh_token' => $this->generateRefreshToken($user),
                'expires_in'    => $accessTtl * 60,
            ];
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException $e) {
            throw new \Exception("Session expired completely. Please login again.");
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            throw new \Exception("This token has already been refreshed.");
        } catch (\Exception $e) {
            throw new \Exception("Refresh failed: " . $e->getMessage());
        }
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

    public function getUserIdFromToken(): ?UserId
    {
        try {
            if ($token = JWTAuth::getToken()) {
                $payload = JWTAuth::getPayload($token);
                return new UserId($payload->get('sub'));
            }
        } catch (\Exception $e) {
        }
        return null;
    }
}
