<?php
// modules/EventStaffingPosition/Application/Command/UpdatePosition/UpdatePositionHandler.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Application\Command\UpdatePosition;

use Modules\EventStaffingPosition\Domain\Repository\EventStaffingPositionRepositoryInterface;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;

final readonly class UpdatePositionHandler
{
    public function __construct(
        private EventStaffingPositionRepositoryInterface $repository,
    ) {
    }

    public function handle(UpdatePositionCommand $command): void
    {
        $position = $this->repository->findById(PositionId::fromString($command->id));

        if ($position === null) {
            throw new \DomainException("Staffing position {$command->id} not found.");
        }

        $position->update(
            title: TranslatableText::fromArray($command->title),
            requirements: TranslatableText::fromArray($command->requirements),
            headcount: $command->headcount,
            wage: new Money($command->wageAmount, $command->wageType),
        );

        $this->repository->save($position);
    }
}
