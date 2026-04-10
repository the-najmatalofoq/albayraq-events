<?php
// modules/EventStaffingGroup/Application/Command/CreateGroup/CreateGroupHandler.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Application\Command\CreateGroup;

use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\EventStaffingGroup\Domain\EventStaffingGroup;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\HexColor;
use Modules\User\Domain\ValueObject\UserId;

final readonly class CreateGroupHandler
{
    public function __construct(
        private EventStaffingGroupRepositoryInterface $repository,
    ) {
    }

    public function handle(CreateGroupCommand $command): GroupId
    {
        $id = $this->repository->nextIdentity();

        $group = EventStaffingGroup::create(
            uuid: $id,
            eventId: EventId::fromString($command->eventId),
            name: TranslatableText::fromArray($command->name),
            color: HexColor::fromString($command->color),
            isLocked: $command->isLocked,
            leaderId: $command->leaderId ? UserId::fromString($command->leaderId) : null,
        );

        $this->repository->save($group);

        return $id;
    }
}
