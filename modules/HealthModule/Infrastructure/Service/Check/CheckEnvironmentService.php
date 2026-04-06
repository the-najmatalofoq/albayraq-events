<?php

declare(strict_types=1);

namespace Modules\HealthModule\Infrastructure\Service\Check;

use Illuminate\Support\Facades\Log;
use Modules\HealthModule\Domain\ValueObject\HealthStatus;

final class CheckEnvironmentService
{
    public function check(): HealthStatus
    {
        $appEnv = app()->environment();
        $appDebug = config('app.debug');
        $appKey = config('app.key');

        $issues = [];

        if (empty($appKey)) {
            $issues[] = 'APP_KEY is not set';
        }

        if ($appDebug && $appEnv === 'production') {
            $issues[] = 'APP_DEBUG is enabled in production';
        }

        $isHealthy = empty($issues);

        if (!$isHealthy) {
            Log::warning('HealthCheck: Environment issues detected', [
                'env' => $appEnv,
                'debug' => $appDebug,
                'issues' => $issues,
            ]);
        }

        return new HealthStatus(
            service: 'environment',
            isHealthy: $isHealthy,
            message: $isHealthy ? 'Environment configuration is valid' : 'Environment issues detected',
            details: [
                'env' => $appEnv,
                'debug' => $appDebug,
                'issues' => $issues,
            ]
        );
    }
}
