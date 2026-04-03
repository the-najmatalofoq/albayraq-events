<?php
// modules/Shared/Infrastructure/Http/Middleware/ResolveLocaleMiddleware.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResolveLocaleMiddleware
{
    // fix: make them config driven, and use private helper methods.
    private const SUPPORTED_LOCALES = ['ar', 'en'];
    private const DEFAULT_LOCALE = 'ar';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        app()->setLocale($locale);
        $request->attributes->set('locale', $locale);

        $response = $next($request);

        if ($response instanceof Response) {
            $response->headers->set('Content-Language', $locale);
        }

        return $response;
    }

    private function resolveLocale(Request $request): string
    {
        $header = $request->header('Accept-Language', self::DEFAULT_LOCALE);
        $locale = strtolower(substr((string) $header, 0, 2));

        return in_array($locale, self::SUPPORTED_LOCALES, true)
            ? $locale
            : self::DEFAULT_LOCALE;
    }
}
