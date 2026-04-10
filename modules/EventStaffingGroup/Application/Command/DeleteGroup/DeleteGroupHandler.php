<?php
// modules/EventStaffingGroup/Application/Command/DeleteGroup/DeleteGroupHandler.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Application\Command\DeleteGroup;

use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;

final readonly class DeleteGroupHandler
{
    public function __construct(
        private EventStaffingGroupRepositoryInterface $repository,
    ) {
    }

    public function handle(string $id): void
    {
        $this->repository->delete(GroupId::fromString($id));
    }
}
