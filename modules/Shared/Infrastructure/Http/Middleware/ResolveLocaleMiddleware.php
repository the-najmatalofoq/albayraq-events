<?php
// modules/Shared/Infrastructure/Http/Middleware/ResolveLocaleMiddleware.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\User\Domain\Enum\LanguageEnum;
use Modules\User\Domain\Repository\UserSettingsRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Symfony\Component\HttpFoundation\Response;

final class ResolveLocaleMiddleware
{
    public function __construct(
        private readonly UserSettingsRepositoryInterface $userSettingsRepository,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {

        $locale = $this->resolveLocale($request);

        app()->setLocale($locale);

        return $next($request);
    }

    private function resolveLocale(Request $request): string
    {
        // 1. Authenticated user's saved preference takes highest priority.
        $authUser = $request->user();

        if ($authUser !== null) {
            $settings = $this->userSettingsRepository->findByUserId(
                new UserId($authUser->id)
            );

            if ($settings !== null) {
                return $settings->preferredLocale->value;
            }
        }

        // 2. Accept-Language request header.
        $headerLocale = $request->header('Accept-Language');

        if ($headerLocale && $this->isSupported($headerLocale)) {

            return $headerLocale;
        }

        // 3. Application default.
        return config('app.locale', LanguageEnum::AR->value);
    }

    private function isSupported(string $locale): bool
    {
        return in_array($locale, LanguageEnum::values(), true);
    }
}
