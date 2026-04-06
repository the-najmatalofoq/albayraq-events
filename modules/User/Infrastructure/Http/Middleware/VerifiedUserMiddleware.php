<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;

final class VerifiedUserMiddleware
{
    public function __construct(private readonly JsonResponder $responder) {}

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || is_null($user->email_verified_at)) {
            return $this->responder->error(
                errorCode: 'EMAIL_NOT_VERIFIED',
                status: 403,
                messageKey: 'auth.email_not_verified'
            );
        }

        return $next($request);
    }
}
