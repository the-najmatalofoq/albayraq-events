<?php
// modules/EventTask/Domain/Repository/EventTaskRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventTask\Domain\Repository;

use Modules\EventTask\Domain\EventTask;
use Modules\EventTask\Domain\ValueObject\TaskId;
use Modules\Event\Domain\ValueObject\EventId;

interface EventTaskRepositoryInterface
{
    public function nextIdentity(): TaskId;

    public function save(EventTask $task): void;

    public function findById(TaskId $id): ?EventTask;

    public function findByEventId(EventId $eventId): array;
}
