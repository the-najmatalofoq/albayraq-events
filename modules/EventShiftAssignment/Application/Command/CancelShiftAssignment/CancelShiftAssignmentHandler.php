<?php
// modules/EventShiftAssignment/Application/Command/CancelShiftAssignment/CancelShiftAssignmentHandler.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Application\Command\CancelShiftAssignment;

use Modules\EventShiftAssignment\Domain\Repository\EventShiftAssignmentRepositoryInterface;
use Modules\EventShiftAssignment\Domain\Exception\ShiftAssignmentNotFoundException;
use Modules\EventShiftAssignment\Domain\ValueObject\ShiftAssignmentId;

final readonly class CancelShiftAssignmentHandler
{
    public function __construct(
        private EventShiftAssignmentRepositoryInterface $repository,
    ) {
    }

    public function handle(CancelShiftAssignmentCommand $command): void
    {
        $id = ShiftAssignmentId::fromString($command->assignmentId);
        $assignment = $this->repository->findById($id);

        if ($assignment === null) {
            throw ShiftAssignmentNotFoundException::create($command->assignmentId);
        }

        $assignment->cancel();
        $this->repository->save($assignment);
    }
}
