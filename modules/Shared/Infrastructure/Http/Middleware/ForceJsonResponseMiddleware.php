<?php
// modules/Shared/Infrastructure/Http/Middleware/ForceJsonResponseMiddleware.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ForceJsonResponseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
