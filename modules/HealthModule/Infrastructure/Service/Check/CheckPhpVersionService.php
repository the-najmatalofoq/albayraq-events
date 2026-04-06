<?php

declare(strict_types=1);

namespace Modules\HealthModule\Infrastructure\Service\Check;

use Modules\HealthModule\Domain\ValueObject\HealthStatus;
use Illuminate\Support\Facades\Log;

final class CheckPhpVersionService
{
    private const REQUIRED_PHP_VERSION = '8.4.0';

    public function check(): HealthStatus
    {
        $currentVersion = PHP_VERSION;
        $isHealthy = version_compare($currentVersion, self::REQUIRED_PHP_VERSION, '>=');

        if (!$isHealthy) {
            Log::warning('HealthCheck: PHP version too low', [
                'current' => $currentVersion,
                'required' => self::REQUIRED_PHP_VERSION,
            ]);
        }

        return new HealthStatus(
            service: 'php_version',
            isHealthy: $isHealthy,
            message: $isHealthy
                ? 'PHP version meets requirements'
                : "PHP version {$currentVersion} is below required " . self::REQUIRED_PHP_VERSION,
            details: [
                'current' => $currentVersion,
                'required' => self::REQUIRED_PHP_VERSION,
            ]
        );
    }
}
