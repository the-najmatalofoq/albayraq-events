<?php

declare(strict_types=1);

namespace Modules\HealthModule\Infrastructure\Service;

use Modules\HealthModule\Domain\Service\HealthCheckerInterface;
use Modules\HealthModule\Domain\ValueObject\HealthStatus;
use Modules\HealthModule\Infrastructure\Service\Check\CheckPhpVersionService;
use Modules\HealthModule\Infrastructure\Service\Check\CheckLaravelVersionService;
use Modules\HealthModule\Infrastructure\Service\Check\CheckDatabaseConnectionService;
use Modules\HealthModule\Infrastructure\Service\Check\CheckCacheConnectionService;
use Modules\HealthModule\Infrastructure\Service\Check\CheckArtisanCommandService;
use Modules\HealthModule\Infrastructure\Service\Check\CheckEnvironmentService;
use Modules\HealthModule\Infrastructure\Service\Check\CheckMigrationsService;

final class LaravelHealthChecker implements HealthCheckerInterface
{
    public function __construct(
        private CheckPhpVersionService $checkPhpVersion,
        private CheckLaravelVersionService $checkLaravelVersion,
        private CheckDatabaseConnectionService $checkDatabaseConnection,
        private CheckCacheConnectionService $checkCacheConnection,
        private CheckArtisanCommandService $checkArtisanCommand,
        private CheckEnvironmentService $checkEnvironment,
        private CheckMigrationsService $checkMigrations,
    ) {
    }

    /**
     * @return array<array-key, HealthStatus>
     */
    public function checkAll(): array
    {
        return [
            $this->checkPhpVersion->check(),
            $this->checkLaravelVersion->check(),
            $this->checkDatabaseConnection->check(),
            $this->checkCacheConnection->check(),
            $this->checkArtisanCommand->check(),
            $this->checkEnvironment->check(),
            $this->checkMigrations->check(),
        ];
    }

    public function isHealthy(): bool
    {
        foreach ($this->checkAll() as $result) {
            if (!$result->isHealthy) {
                return false;
            }
        }

        return true;
    }
}
