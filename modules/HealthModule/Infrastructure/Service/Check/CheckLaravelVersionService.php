<?php

declare(strict_types=1);

namespace Modules\HealthModule\Infrastructure\Service\Check;

use Modules\HealthModule\Domain\ValueObject\HealthStatus;
use Illuminate\Support\Facades\Log;

final class CheckLaravelVersionService
{
    private const REQUIRED_LARAVEL_VERSION = '12.0.0';

    public function check(): HealthStatus
    {
        $currentVersion = app()->version();
        $isHealthy = version_compare($currentVersion, self::REQUIRED_LARAVEL_VERSION, '>=');

        if (!$isHealthy) {
            Log::warning('HealthCheck: Laravel version too low', [
                'current' => $currentVersion,
                'required' => self::REQUIRED_LARAVEL_VERSION,
            ]);
        }

        return new HealthStatus(
            service: 'laravel_version',
            isHealthy: $isHealthy,
            message: $isHealthy
                ? 'Laravel version meets requirements'
                : "Laravel version {$currentVersion} is below required " . self::REQUIRED_LARAVEL_VERSION,
            details: [
                'current' => $currentVersion,
                'required' => self::REQUIRED_LARAVEL_VERSION,
            ]
        );
    }
}
