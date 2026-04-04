<?php

declare(strict_types=1);

namespace Modules\HealthModule\Domain\Service;

use Modules\HealthModule\Domain\ValueObject\HealthStatus;

interface HealthCheckerInterface
{
    /**
     * @return array<array-key, HealthStatus>
     */
    public function checkAll(): array;

    public function isHealthy(): bool;
}
