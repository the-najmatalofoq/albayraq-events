<?php
// modules/Shared/Infrastructure/Http/Middleware/ResolveLocaleMiddleware.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResolveLocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales', ['ar', 'en']);
        $locale = $request->header('Accept-Language');

        if (!$locale || !in_array($locale, $supportedLocales, true)) {
            $locale = config('app.locale', 'ar');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
