<?php
// modules/IAM/Infrastructure/Services/JwtTokenManager.php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Services;

use Modules\IAM\Domain\Service\TokenManagerInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;

final class JwtTokenManager implements TokenManagerInterface
{
    public function issueFromUserId(UserId $userId): array
    {
        $user = UserModel::findOrFail($userId->value);
        $token = auth('api')->login($user);

        return $this->respondWithToken($token);
    }

    public function refresh(): array
    {
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function invalidate(): void
    {
        auth('api')->logout();
    }

    public function getUserIdFromToken(): ?UserId
    {
        $user = auth('api')->user();
        return $user ? new UserId($user->id) : null;
    }

    private function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }
}
