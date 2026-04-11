<?php
// modules/EventJoinRequest/Domain/Repository/EventJoinRequestRepositoryInterface.php
declare(strict_types=1);
namespace Modules\EventJoinRequest\Domain\Repository;

use Modules\EventJoinRequest\Domain\EventJoinRequest;
use Modules\EventJoinRequest\Domain\ValueObject\JoinRequestId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Event\Domain\ValueObject\EventId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface EventJoinRequestRepositoryInterface
{
    public function nextIdentity(): JoinRequestId;
    public function save(EventJoinRequest $request): void;
    public function findById(JoinRequestId $id): ?EventJoinRequest;
    public function findByUserAndEvent(UserId $userId, EventId $eventId): ?EventJoinRequest;
    /** @return EventJoinRequest[] */
    public function findByEventId(EventId $eventId): array;
    /** @return EventJoinRequest[] */
    public function findByUserId(UserId $userId): array;
    public function delete(JoinRequestId $id): void;
}