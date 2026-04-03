<?php
// modules/IAM/Infrastructure/Auth/JwtTokenManager.php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Auth;

use Modules\IAM\Domain\Service\TokenManagerInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
// fix: we have two files are JwtTokenManager, keep the one in the IAM and in the Service folder but merge the two to make them powerfulls
final readonly class JwtTokenManager implements TokenManagerInterface
{
    public function issueFromUserId(UserId $userId): array
    {
        // fix: the ->value() method.
        $token = JWTAuth::fromUser(
            UserModel::findOrFail($userId->value())
        );

        return $this->respondWithToken($token);
    }

    public function refresh(): array
    {
        try {
            $token = JWTAuth::refresh();
            return $this->respondWithToken((string) $token);
        } catch (JWTException $e) {
            throw new \RuntimeException('Could not refresh token', 401, $e);
        }
    }

    public function invalidate(): void
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
        }
    }

    public function getUserIdFromToken(): ?UserId
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $user ? new UserId($user->id) : null;
        } catch (JWTException $e) {
            return null;
        }
    }

    private function respondWithToken(string $token): array
    {
        // fix: validate the config values and use variables
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'refresh_expires_in' => config('jwt.refresh_ttl') * 60,
        ];
    }
}
