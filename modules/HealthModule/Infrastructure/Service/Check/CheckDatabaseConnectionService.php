<?php

declare(strict_types=1);

namespace Modules\HealthModule\Infrastructure\Service\Check;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\HealthModule\Domain\ValueObject\HealthStatus;

final class CheckDatabaseConnectionService
{
    public function check(): HealthStatus
    {
        try {
            DB::connection()->getPdo();
            $databaseName = DB::connection()->getDatabaseName();

            return new HealthStatus(
                service: 'database',
                isHealthy: true,
                message: 'Database connection successful',
                details: [
                    'database' => $databaseName,
                    'connection' => config('database.default'),
                ]
            );
        } catch (\Exception $e) {
            Log::error('HealthCheck: Database connection failed', [
                'error' => $e->getMessage(),
            ]);

            return new HealthStatus(
                service: 'database',
                isHealthy: false,
                message: 'Database connection failed',
                details: [
                    'error' => $e->getMessage(),
                ]
            );
        }
    }
}
