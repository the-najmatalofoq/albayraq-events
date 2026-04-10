<?php
// modules/EventStaffingPosition/Application/Command/CreatePosition/CreatePositionHandler.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Application\Command\CreatePosition;

use Modules\EventStaffingPosition\Domain\Repository\EventStaffingPositionRepositoryInterface;
use Modules\EventStaffingPosition\Domain\EventStaffingPosition;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\Event\Domain\Repository\EventRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;

final readonly class CreatePositionHandler
{
    public function __construct(
        private EventStaffingPositionRepositoryInterface $repository,
        private EventRepositoryInterface $eventRepository,
    ) {
    }

    public function handle(CreatePositionCommand $command): PositionId
    {
        $eventId = EventId::fromString($command->eventId);

        if ($this->eventRepository->findById($eventId) === null) {
            throw new \DomainException("Event {$command->eventId} not found.");
        }

        $id = $this->repository->nextIdentity();
        $position = EventStaffingPosition::create(
            uuid: $id,
            eventId: $eventId,
            title: TranslatableText::fromArray($command->title),
            requirements: TranslatableText::fromArray($command->requirements),
            headcount: $command->headcount,
            wage: new Money($command->wageAmount, $command->wageType),
            isActive: $command->isAnnounced,
        );

        $this->repository->save($position);
        return $id;
    }
}
