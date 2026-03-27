<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Shared\Application\EventDispatcher;
use Modules\Shared\Domain\Service\LoggerInterface;
use Modules\Shared\Domain\Service\TranslatorInterface;
use Modules\Shared\Infrastructure\Messaging\LaravelEventDispatcher;
use Modules\Shared\Infrastructure\Services\LaravelTranslator;
use Modules\Shared\Infrastructure\Services\Logger;
use Modules\Shared\Presentation\Http\JsonResponder;

final class SharedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            EventDispatcher::class,
            LaravelEventDispatcher::class
        );

         $this->app->bind(LoggerInterface::class, function ($app) {
            return new Logger('daily');
        });

        $this->app->bind(TranslatorInterface::class, LaravelTranslator::class);

        $this->app->bind(JsonResponder::class, function ($app) {
            return new JsonResponder($app->make(TranslatorInterface::class));
        });
    }
}
