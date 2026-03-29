<?php

namespace Modules\EventParticipationBadge\Domain\Repository;

use Modules\EventParticipationBadge\Domain\EventParticipationBadge;

interface EventParticipationBadgeRepositoryInterface
{
    public function findById(string $id): ?EventParticipationBadge;

    public function save(EventParticipationBadge $badge): void;
}
