<?php
// bootstrap/app.php
declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Infrastructure\Http\Middleware\{
    ForceJsonResponseMiddleware,
    ResolveLocaleMiddleware
};
use Modules\User\Infrastructure\Http\Middleware\EnsureActiveJoinRequest;
use Modules\User\Infrastructure\Http\Middleware\HasApprovedJoinRequestMiddleware;
use Modules\User\Infrastructure\Http\Middleware\VerifiedUserMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withBroadcasting(__DIR__ . '/../routes/channels.php')
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            ForceJsonResponseMiddleware::class,
            ResolveLocaleMiddleware::class,
        ]);

        $middleware->alias([
            'locale' => ResolveLocaleMiddleware::class,
            'force-json' => ForceJsonResponseMiddleware::class,
            'join-request.active' => EnsureActiveJoinRequest::class,
            'verified' => VerifiedUserMiddleware::class,
            'join-request.approved' => HasApprovedJoinRequestMiddleware::class,
            'session.validate' => \Modules\IAM\Presentation\Http\Middleware\ValidateJwtSessionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if (!$request->expectsJson()) {
                return null;
            }

            $responder = app(JsonResponder::class);

            if ($e instanceof ValidationException) {
                return $responder->validationError($e->errors());
            }

            if ($e instanceof DomainException) {
                return $responder->error(
                    errorCode: $e->getErrorCode()->value,
                    status: $e->getErrorCode()->getHttpStatus(),
                    messageKey: $e->getMessageKey(),
                    errors: $e->getErrors(),
                    messageParams: $e->getMessageParams()
                );
            }

            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $errorCode = match ($status) {
                    401 => 'UNAUTHORIZED',
                    403 => 'FORBIDDEN',
                    404 => 'NOT_FOUND',
                    429 => 'TOO_MANY_REQUESTS',
                    default => 'HTTP_ERROR',
                };

                return $responder->error(
                    errorCode: $errorCode,
                    status: $status,
                    messageKey: "messages.errors.{$errorCode}"
                );
            }

            return $responder->error(
                errorCode: 'INTERNAL_ERROR',
                status: 500,
                messageKey: config('app.debug') ? $e->getMessage() : 'errors.INTERNAL_ERROR'
            );
        });
    })->create();
