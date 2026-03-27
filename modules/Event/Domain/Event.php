<?php
// modules/Event/Domain/Event.php
declare(strict_types=1);

namespace Modules\Event\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\GeoPoint;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Event\Domain\Enum\EventStatusEnum;

final class Event extends AggregateRoot
{
    public function __construct(
        public readonly EventId $uuid,
        public private(set) TranslatableText $name,
        public private(set) string $slug,
        public private(set) TranslatableText $description,
        public private(set) string $type,
        public private(set) \DateTimeImmutable $startDate,
        public private(set) \DateTimeImmutable $endDate,
        public private(set) GeoPoint $location,
        public private(set) Money $price,
        public private(set) EventStatusEnum $status = EventStatusEnum::DRAFT,
        public private(set) ?string $bannerId = null
    ) {}

    public static function create(
        EventId $uuid,
        TranslatableText $name,
        string $slug,
        TranslatableText $description,
        string $type,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        GeoPoint $location,
        Money $price,
        ?string $bannerId = null
    ): self {
        return new self(
            $uuid,
            $name,
            $slug,
            $description,
            $type,
            $startDate,
            $endDate,
            $location,
            $price,
            EventStatusEnum::DRAFT,
            $bannerId
        );
    }

    public function publish(): void
    {
        if ($this->status !== EventStatusEnum::DRAFT) {
            throw new \DomainException("Only draft events can be published.");
        }
        $this->status = EventStatusEnum::PUBLISHED;
    }

    public function start(): void
    {
        $this->status = EventStatusEnum::ONGOING;
    }

    public function complete(): void
    {
        $this->status = EventStatusEnum::COMPLETED;
    }

    public function cancel(): void
    {
        $this->status = EventStatusEnum::CANCELLED;
    }

    public function updateBanner(string $bannerId): void
    {
        $this->bannerId = $bannerId;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
