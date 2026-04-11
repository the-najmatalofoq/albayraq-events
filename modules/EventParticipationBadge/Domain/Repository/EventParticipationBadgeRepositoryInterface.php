<?php

namespace Modules\EventParticipationBadge\Domain\Repository;

use Modules\EventParticipationBadge\Domain\EventParticipationBadge;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface EventParticipationBadgeRepositoryInterface
{
    public function findById(string $id): ?EventParticipationBadge;

    public function save(EventParticipationBadge $badge): void;
}
