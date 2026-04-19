<?php

declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Services;

use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\IAM\Infrastructure\Persistence\Eloquent\Models\UserSessionModel;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

final readonly class JwtTokenManager implements TokenManager
{
    public function createToken(string $userId, array $metadata = []): array
    {
        $user = UserModel::findOrFail($userId);

        // Generate a new unique session ID
        $sessionId = (string) Str::uuid();

        // Save session to database
        UserSessionModel::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'device_name' => $metadata['device_name'] ?? null,
            'is_active' => true,
        ]);

        $accessTtl = (int) config('jwt.ttl');

        return [
            'access_token'  => $this->generateAccessToken($user, $sessionId, $accessTtl),
            'refresh_token' => $this->generateRefreshToken($user, $sessionId),
            'expires_in'    => $accessTtl * 60,
            'token_type'    => 'Bearer',
        ];
    }

    private function generateAccessToken(UserModel $user, string $sessionId, int $ttl): string
    {
        JWTAuth::factory()->setTTL($ttl);

        return JWTAuth::claims(['session_id' => $sessionId])->fromUser($user);
    }

    private function generateRefreshToken(UserModel $user, string $sessionId): string
    {
        $ttl = (int) config('jwt.refresh_ttl');

        JWTAuth::factory()->setTTL($ttl);

        return JWTAuth::claims([
            'type' => 'refresh',
            'session_id' => $sessionId
        ])->fromUser($user);
    }

    public function revokeAllTokens(string $userId): void
    {
        UserSessionModel::where('user_id', $userId)
            ->update(['is_active' => false]);

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
            $sessionId = $payload->get('session_id');

            $newToken = JWTAuth::refresh($oldToken);
            $user = UserModel::find($userId);

            if (!$user) {
                throw new \Exception("User ID [{$userId}] not found in database.");
            }

            $accessTtl = (int) config('jwt.ttl');

            return [
                'access_token'  => $newToken,
                'refresh_token' => $this->generateRefreshToken($user, (string) $sessionId),
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
                $payload = JWTAuth::getPayload($token);
                $sessionId = $payload->get('session_id');

                if ($sessionId) {
                    UserSessionModel::where('session_id', $sessionId)
                        ->update(['is_active' => false]);
                }

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
