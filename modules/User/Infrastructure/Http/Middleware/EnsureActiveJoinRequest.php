<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Symfony\Component\HttpFoundation\Response;

final readonly class EnsureActiveJoinRequest
{
    public function __construct(
        private UserJoinRequestRepositoryInterface $joinRequestRepository,
        private JsonResponder $responder,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $this->responder->unauthorized('auth.unauthorized');
        }

        $userId = new UserId($user->id);
        $joinRequest = $this->joinRequestRepository->findLatestByUserId($userId);

        if ($joinRequest === null || !$joinRequest->isActive()) {
            return $this->responder->forbidden('auth.join_request_not_active');
        }

        return $next($request);
    }
}
