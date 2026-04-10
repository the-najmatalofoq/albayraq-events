<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class EnsureRoleLevelMiddleware
{
    public function __construct(
        private TokenManager $tokenManager,
        private UserRepositoryInterface $userRepository,
        private JsonResponder $responder,
    ) {}

    /** @param list<string> $roles */
    public function handle(Request $request, Closure $next, ...$roles): mixed
    {
        $userId = $this->tokenManager->getUserIdFromToken();
        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $user = $this->userRepository->findById($userId);
        if ($user === null) {
            return $this->responder->unauthorized();
        }

        $userRoles = array_map(fn($roleId) => $roleId->value, $user->roleIds);

        $hasRole = false;
        foreach ($roles as $role) {
            if (in_array($role, $userRoles, true)) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            return $this->responder->forbidden(
                messageKey: 'auth.insufficient_permissions'
            );
        }

        return $next($request);
    }
}
