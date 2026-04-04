<?php

declare(strict_types=1);

namespace Modules\HealthModule\Infrastructure\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Modules\HealthModule\Domain\Service\HealthCheckerInterface;
use Modules\HealthModule\Domain\ValueObject\HealthStatus;

final class LaravelHealthChecker implements HealthCheckerInterface
{
    private const REQUIRED_PHP_VERSION = '8.4.0';
    private const REQUIRED_LARAVEL_VERSION = '12.0.0';

    private array $results = [];

    public function checkAll(): array
    {
        $this->results = [];

        $this->checkPhpVersion();
        $this->checkLaravelVersion();
        $this->checkDatabaseConnection();
        $this->checkCacheConnection();
        $this->checkArtisanCommand();
        $this->checkEnvironment();
        $this->checkMigrations();

        return $this->results;
    }

    public function isHealthy(): bool
    {
        if (empty($this->results)) {
            $this->checkAll();
        }

        foreach ($this->results as $result) {
            if (!$result->isHealthy) {
                return false;
            }
        }

        return true;
    }

    private function checkPhpVersion(): void
    {
        $currentVersion = PHP_VERSION;
        $isHealthy = version_compare($currentVersion, self::REQUIRED_PHP_VERSION, '>=');

        $this->results[] = new HealthStatus(
            service: 'php_version',
            isHealthy: $isHealthy,
            message: $isHealthy ? 'PHP version meets requirements' : "PHP version {$currentVersion} is below required " . self::REQUIRED_PHP_VERSION,
            details: ['current' => $currentVersion, 'required' => self::REQUIRED_PHP_VERSION]
        );
    }

    private function checkLaravelVersion(): void
    {
        $currentVersion = app()->version();
        $isHealthy = version_compare($currentVersion, self::REQUIRED_LARAVEL_VERSION, '>=');

        $this->results[] = new HealthStatus(
            service: 'laravel_version',
            isHealthy: $isHealthy,
            message: $isHealthy ? 'Laravel version meets requirements' : "Laravel version {$currentVersion} is below required " . self::REQUIRED_LARAVEL_VERSION,
            details: ['current' => $currentVersion, 'required' => self::REQUIRED_LARAVEL_VERSION]
        );
    }

    private function checkDatabaseConnection(): void
    {
        try {
            DB::connection()->getPdo();
            $databaseName = DB::connection()->getDatabaseName();

            $this->results[] = new HealthStatus(
                service: 'database',
                isHealthy: true,
                message: 'Database connection successful',
                details: ['database' => $databaseName, 'connection' => config('database.default')]
            );
        } catch (\Exception $e) {
            $this->results[] = new HealthStatus(
                service: 'database',
                isHealthy: false,
                message: 'Database connection failed',
                details: ['error' => $e->getMessage()]
            );
        }
    }

    private function checkCacheConnection(): void
    {
        $cacheDriver = config('cache.default');
        $cacheStore = Cache::store($cacheDriver);

        try {
            $testKey = 'health_check_' . uniqid();
            $cacheStore->put($testKey, 'ok', 10);
            $value = $cacheStore->get($testKey);
            $cacheStore->forget($testKey);

            $isHealthy = $value === 'ok';

            $this->results[] = new HealthStatus(
                service: 'cache',
                isHealthy: $isHealthy,
                message: $isHealthy ? 'Cache connection successful' : 'Cache read/write failed',
                details: ['driver' => $cacheDriver]
            );
        } catch (\Exception $e) {
            $this->results[] = new HealthStatus(
                service: 'cache',
                isHealthy: false,
                message: 'Cache connection failed',
                details: ['driver' => $cacheDriver, 'error' => $e->getMessage()]
            );
        }
    }

    private function checkArtisanCommand(): void
    {
        try {
            $exitCode = Artisan::call('list', ['--raw' => true]);
            $isHealthy = $exitCode === 0;

            $this->results[] = new HealthStatus(
                service: 'artisan',
                isHealthy: $isHealthy,
                message: $isHealthy ? 'Artisan commands are accessible' : 'Artisan command failed',
                details: ['exit_code' => $exitCode]
            );
        } catch (\Exception $e) {
            $this->results[] = new HealthStatus(
                service: 'artisan',
                isHealthy: false,
                message: 'Artisan command failed',
                details: ['error' => $e->getMessage()]
            );
        }
    }

    private function checkEnvironment(): void
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

        $this->results[] = new HealthStatus(
            service: 'environment',
            isHealthy: $isHealthy,
            message: $isHealthy ? 'Environment configuration is valid' : 'Environment issues detected',
            details: ['env' => $appEnv, 'debug' => $appDebug, 'issues' => $issues]
        );
    }

    private function checkMigrations(): void
    {
        try {
            $migrations = DB::table('migrations')->count();
            $isHealthy = true;

            $this->results[] = new HealthStatus(
                service: 'migrations',
                isHealthy: $isHealthy,
                message: "{$migrations} migrations have been run",
                details: ['total_migrations' => $migrations]
            );
        } catch (\Exception $e) {
            $this->results[] = new HealthStatus(
                service: 'migrations',
                isHealthy: false,
                message: 'Migrations table not found or not accessible',
                details: ['error' => $e->getMessage()]
            );
        }
    }
}
