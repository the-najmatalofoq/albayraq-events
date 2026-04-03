<?php
// modules/Shared/Infrastructure/Providers/SecurityServiceProvider.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

final class SecurityServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configurePasswordDefaults();
        $this->configureRateLimiting();
    }

    private function configurePasswordDefaults(): void
    {
        Password::defaults(
            fn() =>
            $this->app->environment('production')
            ? Password::min(8)->uncompromised()->mixedCase()->numbers()->symbols()
            : Password::min(8)
        );
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('auth', fn(Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('api', fn(Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));
    }
}
