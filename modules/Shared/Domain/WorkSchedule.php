<?php
// modules/Shared/Domain/WorkSchedule.php
declare(strict_types=1);

namespace Modules\Shared\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\ScheduleId;

final class WorkSchedule extends AggregateRoot
{
    private function __construct(
        public readonly ScheduleId $uuid,
        public readonly string $schedulableId,
        public readonly string $schedulableType,
        public private(set) array $daysOfWeek,
        public private(set) string $startTime,
        public private(set) string $endTime,
        public private(set) bool $isActive = true,
    ) {}

    public static function create(
        ScheduleId $uuid,
        string $schedulableId,
        string $schedulableType,
        array $daysOfWeek,
        string $startTime,
        string $endTime,
    ): self {
        return new self($uuid, $schedulableId, $schedulableType, $daysOfWeek, $startTime, $endTime);
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
