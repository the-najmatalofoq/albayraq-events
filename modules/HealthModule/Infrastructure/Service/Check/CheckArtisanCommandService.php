<?php

declare(strict_types=1);

namespace Modules\HealthModule\Infrastructure\Service\Check;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Modules\HealthModule\Domain\ValueObject\HealthStatus;

final class CheckArtisanCommandService
{
    public function check(): HealthStatus
    {
        try {
            $exitCode = Artisan::call('list', ['--raw' => true]);
            $isHealthy = $exitCode === 0;

            if (!$isHealthy) {
                Log::error('HealthCheck: Artisan command failed', [
                    'exit_code' => $exitCode,
                ]);
            }

            return new HealthStatus(
                service: 'artisan',
                isHealthy: $isHealthy,
                message: $isHealthy ? 'Artisan commands are accessible' : 'Artisan command failed',
                details: [
                    'exit_code' => $exitCode,
                ]
            );
        } catch (\Exception $e) {
            Log::error('HealthCheck: Artisan command failed', [
                'error' => $e->getMessage(),
            ]);

            return new HealthStatus(
                service: 'artisan',
                isHealthy: false,
                message: 'Artisan command failed',
                details: [
                    'error' => $e->getMessage(),
                ]
            );
        }
    }
}
