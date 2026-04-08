<?php
// modules/WorkSchedule/Domain/WorkSchedule.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\ScheduleId;

final class WorkSchedule extends AggregateRoot
{
    private function __construct(
        public readonly ScheduleId $uuid,
        public readonly string $schedulableId,
        public readonly string $schedulableType,
        public private(set) \DateTimeImmutable $date,
        public private(set) string $startTime,
        public private(set) string $endTime,
        public private(set) bool $isActive = true,
    ) {}

    public static function create(
        ScheduleId $uuid,
        string $schedulableId,
        string $schedulableType,
        \DateTimeImmutable $date,
        string $startTime,
        string $endTime,
        bool $isActive = true,
    ): self {
        return new self($uuid, $schedulableId, $schedulableType, $date, $startTime, $endTime, $isActive);
    }

    public function update(
        ?\DateTimeImmutable $date = null,
        ?string $startTime = null,
        ?string $endTime = null,
        ?bool $isActive = null,
    ): void {
        if ($date !== null) {
            $this->date = $date;
        }

        if ($startTime !== null) {
            $this->startTime = $startTime;
        }

        if ($endTime !== null) {
            $this->endTime = $endTime;
        }

        if ($isActive !== null) {
            $this->isActive = $isActive;
        }
    }

    public function activate(): void
    {
        $this->isActive = true;
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
