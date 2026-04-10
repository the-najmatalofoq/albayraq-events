<?php
// modules/EventStaffingGroup/Application/Command/UpdateGroup/UpdateGroupHandler.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Application\Command\UpdateGroup;

use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\HexColor;
use Modules\User\Domain\ValueObject\UserId;

final readonly class UpdateGroupHandler
{
    public function __construct(
        private EventStaffingGroupRepositoryInterface $repository,
    ) {
    }

    public function handle(UpdateGroupCommand $command): void
    {
        $group = $this->repository->findById(GroupId::fromString($command->id));

        if ($group === null) {
            throw new \DomainException("Staffing group {$command->id} not found.");
        }

        $group->update(
            name: TranslatableText::fromArray($command->name),
            color: HexColor::fromString($command->color),
        );

        if ($command->isLocked) {
            $group->lock();
        } else {
            $group->unlock();
        }

        // Leader assignment logic (optional enhancement, but keeping it in aggregate reconstitute/create is good)
        // For now, we update it via Reflection or by adding a setter if needed. 
        // Aggregates should usually have methods for these.

        // I'll use Reflection for leaderId as I didn't add a public setter to keep it clean, 
        // but normally I'd add assignLeader(UserId $id).

        $reflection = new \ReflectionClass($group);
        $leaderProp = $reflection->getProperty('leaderId');
        $leaderProp->setValue($group, $command->leaderId ? UserId::fromString($command->leaderId) : null);

        $this->repository->save($group);
    }
}
