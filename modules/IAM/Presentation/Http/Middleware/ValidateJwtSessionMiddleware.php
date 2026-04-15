<?php

declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\IAM\Infrastructure\Persistence\Eloquent\Models\UserSessionModel;

final readonly class ValidateJwtSessionMiddleware
{
    public function __construct(
        private JsonResponder $responder
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // 1. Check if token exists in the request
            if (!$token = JWTAuth::getToken()) {
                return $next($request);
            }

            // 2. Parse payload directly
            $payload = JWTAuth::setToken($token)->getPayload();
            $sessionId = $payload->get('session_id');
            $userId = $payload->get('sub');

            // 3. If it's a session-managed token, validate it
            if ($sessionId && $userId) {
                $session = UserSessionModel::where('session_id', $sessionId)
                    ->where('user_id', $userId)
                    ->where('is_active', true)
                    ->first();

                if (!$session) {
                    return $this->responder->error(
                        errorCode: 'UNAUTHORIZED',
                        status: 401,
                        messageKey: __('messages.auth.session_invalidated')
                    );
                }
            }
        } catch (\Exception $e) {
            // If token is invalid/expired, let 'auth:api' handle the 401
        }

        return $next($request);
    }
}
