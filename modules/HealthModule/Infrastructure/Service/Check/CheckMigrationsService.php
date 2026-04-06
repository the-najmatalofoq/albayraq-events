<?php

declare(strict_types=1);

namespace Modules\HealthModule\Infrastructure\Service\Check;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\HealthModule\Domain\ValueObject\HealthStatus;

final class CheckMigrationsService
{
    public function check(): HealthStatus
    {
        try {
            $migrations = DB::table('migrations')->count();

            return new HealthStatus(
                service: 'migrations',
                isHealthy: true,
                message: "{$migrations} migrations have been run",
                details: [
                    'total_migrations' => $migrations,
                ]
            );
        } catch (\Exception $e) {
            Log::error('HealthCheck: Migration table error', [
                'error' => $e->getMessage(),
            ]);

            return new HealthStatus(
                service: 'migrations',
                isHealthy: false,
                message: 'Migrations table not found or not accessible',
                details: [
                    'error' => $e->getMessage(),
                ]
            );
        }
    }
}
