<?php

namespace Modules\EventParticipationBadge\Domain;

use DateTimeImmutable;

class EventParticipationBadge
{
    public function __construct(
        public string $id,
        public string $eventParticipationId,
        public ?array $badgeData,
        public ?DateTimeImmutable $generatedAt,
    ) {}
}
