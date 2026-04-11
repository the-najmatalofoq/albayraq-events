<?php
// modules/EventParticipation/Domain/Repository/EventParticipationRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventParticipation\Domain\Repository;

use Modules\EventParticipation\Domain\EventParticipation;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Event\Domain\ValueObject\EventId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface EventParticipationRepositoryInterface
{
    public function nextIdentity(): ParticipationId;

    public function save(EventParticipation $participation): void;

    public function findById(ParticipationId $id): ?EventParticipation;

    public function findByUserId(UserId $userId): array;

    public function findByEventId(EventId $eventId): array;
}
