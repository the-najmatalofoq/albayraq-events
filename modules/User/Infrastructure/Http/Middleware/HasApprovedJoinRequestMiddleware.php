<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final class HasApprovedJoinRequestMiddleware
{
    public function __construct(
        private readonly UserJoinRequestRepositoryInterface $joinRequestRepository,
        private readonly JsonResponder $responder
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            return $this->responder->unauthorized();
        }

        $latestRequest = $this->joinRequestRepository->findLatestByUserId(
            new UserId($user->id)
        );

        if (!$latestRequest || !$latestRequest->isActive()) {
            return $this->responder->error(
                errorCode: 'JOIN_REQUEST_NOT_APPROVED',
                status: 403,
                messageKey: 'user.join_request.not_approved'
            );
        }

        return $next($request);
    }
}
