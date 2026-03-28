<?php
// modules/Event/Domain/Event.php
declare(strict_types=1);

namespace Modules\Event\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Event\Domain\Enum\EventStatusEnum;
use Modules\User\Domain\ValueObject\UserId;

final class Event extends AggregateRoot
{
    private function __construct(
        public readonly EventId $uuid,
        public private(set) TranslatableText $name,
        public private(set) ?TranslatableText $description,
        public private(set) float $latitude,
        public private(set) float $longitude,
        public private(set) int $geofenceRadius,
        public private(set) ?array $address,
        public private(set) DateTimeImmutable $startDate,
        public private(set) DateTimeImmutable $endDate,
        public private(set) string $dailyStartTime,
        public private(set) string $dailyEndTime,
        public private(set) ?TranslatableText $employmentTerms,
        public private(set) EventStatusEnum $status,
        public readonly UserId $createdBy,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?DateTimeImmutable $updatedAt = null,
        public private(set) ?DateTimeImmutable $deletedAt = null,
    ) {}

    public static function create(
        EventId $uuid,
        TranslatableText $name,
        ?TranslatableText $description,
        float $latitude,
        float $longitude,
        int $geofenceRadius,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        string $dailyStartTime,
        string $dailyEndTime,
        UserId $createdBy,
        ?array $address = null,
        ?TranslatableText $employmentTerms = null,
    ): self {
        return new self(
            uuid: $uuid,
            name: $name,
            description: $description,
            latitude: $latitude,
            longitude: $longitude,
            geofenceRadius: $geofenceRadius,
            address: $address,
            startDate: $startDate,
            endDate: $endDate,
            dailyStartTime: $dailyStartTime,
            dailyEndTime: $dailyEndTime,
            employmentTerms: $employmentTerms,
            status: EventStatusEnum::DRAFT,
            createdBy: $createdBy,
            createdAt: new DateTimeImmutable(),
        );
    }

    public function updateStatus(EventStatusEnum $status): void
    {
        $this->status = $status;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
