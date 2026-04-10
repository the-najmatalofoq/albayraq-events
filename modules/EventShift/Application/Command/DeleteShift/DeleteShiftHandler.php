<?php
// modules/EventShift/Application/Command/DeleteShift/DeleteShiftHandler.php
declare(strict_types=1);

namespace Modules\EventShift\Application\Command\DeleteShift;

use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\EventShift\Domain\ValueObject\ShiftId;

final readonly class DeleteShiftHandler
{
    public function __construct(
        private EventShiftRepositoryInterface $repository,
    ) {
    }

    public function handle(string $id): void
    {
        $this->repository->delete(ShiftId::fromString($id));
    }
}
