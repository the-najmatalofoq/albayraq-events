<?php
// modules/Event/Domain/Repository/EventRepositoryInterface.php
declare(strict_types=1);
namespace Modules\Event\Domain\Repository;

use Modules\Event\Domain\Event;
use Modules\Event\Domain\ValueObject\EventId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface EventRepositoryInterface
{
    public function nextIdentity(): EventId;
    public function save(Event $event): void;
    public function findById(EventId $id): ?Event;
    public function findBySlug(string $slug): ?Event;
    public function listAll(): array;
    /** @return Event[] */
    public function findByIds(array $ids): array;   // string[] raw UUIDs
    public function delete(EventId $id): void;
}