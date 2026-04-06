<?php

declare(strict_types=1);

namespace Modules\HealthModule\Infrastructure\Service\Check;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\HealthModule\Domain\ValueObject\HealthStatus;

final class CheckCacheConnectionService
{
    public function check(): HealthStatus
    {
        $cacheDriver = config('cache.default');
        $cacheStore = Cache::store($cacheDriver);

        try {
            $testKey = 'health_check_' . uniqid();
            $cacheStore->put($testKey, 'ok', 10);
            $value = $cacheStore->get($testKey);
            $cacheStore->forget($testKey);

            $isHealthy = $value === 'ok';

            if (!$isHealthy) {
                Log::error('HealthCheck: Cache read/write failed', [
                    'driver' => $cacheDriver,
                ]);
            }

            return new HealthStatus(
                service: 'cache',
                isHealthy: $isHealthy,
                message: $isHealthy ? 'Cache connection successful' : 'Cache read/write failed',
                details: [
                    'driver' => $cacheDriver,
                ]
            );
        } catch (\Exception $e) {
            Log::error('HealthCheck: Cache connection failed', [
                'driver' => $cacheDriver,
                'error' => $e->getMessage(),
            ]);

            return new HealthStatus(
                service: 'cache',
                isHealthy: false,
                message: 'Cache connection failed',
                details: [
                    'driver' => $cacheDriver,
                    'error' => $e->getMessage(),
                ]
            );
        }
    }
}
