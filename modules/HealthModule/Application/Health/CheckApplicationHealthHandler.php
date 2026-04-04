<?php

declare(strict_types=1);

namespace Modules\HealthModule\Application\Health;

use Modules\HealthModule\Domain\Service\HealthCheckerInterface;
use Illuminate\Support\Carbon;

final readonly class CheckApplicationHealthHandler
{
    public function __construct(
        private HealthCheckerInterface $healthChecker,
    ) {
    }

    public function handle(): array
    {
        $statuses = $this->healthChecker->checkAll();
        $isHealthy = $this->healthChecker->isHealthy();

        return [
            'healthy' => $isHealthy,
            'timestamp' => Carbon::now()->toIso8601String(),
            'checks' => array_map(fn($status) => $status->toArray(), $statuses),
        ];
    }
}
