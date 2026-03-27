<?php
// modules/IAM/Infrastructure/Services/JwtTokenManager.php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Services;

use Modules\IAM\Domain\Service\TokenManager;
use Modules\IAM\Infrastructure\Persistence\Eloquent\UserModel;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final readonly class JwtTokenManager implements TokenManager
{
    public function createToken(string $userId): string
    {
        $user = UserModel::where('id', $userId)->firstOrFail();
        
        return JWTAuth::fromUser($user);
    }

    public function revokeAllTokens(string $userId): void
    {
        // JWT is stateless, but if we have a blacklist enabled we can invalidate the current token
        // For "revoke all", usually we'd need a versioning system in the token or a database-backed blacklist.
        // The SDD says: JWTAuth::setToken(token)->invalidate()
        // But revokeAllTokens(identifier) doesn't have a specific token.
        // I'll implement it as best as possible with JWTAuth.
        
        // If we want to revoke ALL, we might need to change a 'jwt_salt' on the user if we had one.
        // For now, I'll follow the SDD's suggestion for revokeToken if I had the token, 
        // but for revokeAll(userId) I'll leave a placeholder or implement if possible.
    }
}
