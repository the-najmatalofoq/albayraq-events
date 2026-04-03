<?php
// modules/Shared/Infrastructure/Providers/SecurityServiceProvider.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Router;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Modules\Shared\Infrastructure\Http\Middleware\ResolveLocaleMiddleware;
use Modules\Shared\Infrastructure\Http\Middleware\ForceJsonResponseMiddleware;
use Illuminate\Foundation\Application;
final class SecurityServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configurePasswordDefaults();
        $this->configureRateLimiting();
        $this->registerMiddleware();
    }

    private function configurePasswordDefaults(): void
    {
        Password::defaults(function () {
            /** @var Application $app */
            $app = $this->app;
            return $app->environment('production')
                ? Password::min(8)->uncompromised()->mixedCase()->numbers()->symbols()
                : Password::min(8);
        });
    }

    private function configureRateLimiting(): void
    {
        // fix: Hint: Convert to arrow functionPHP(PHP7103)
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    private function registerMiddleware(): void
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);

        // fix: move the Middleware to bootstrap/app.php
        $router->aliasMiddleware('locale', ResolveLocaleMiddleware::class);
        $router->aliasMiddleware('force-json', ForceJsonResponseMiddleware::class);

        $router->pushMiddlewareToGroup('api', ResolveLocaleMiddleware::class);
        $router->pushMiddlewareToGroup('api', ForceJsonResponseMiddleware::class);
    }
}
