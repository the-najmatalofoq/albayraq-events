<?php
// modules/EventShift/Presentation/Http/Action/DeleteShiftAction.php
declare(strict_types=1);

namespace Modules\EventShift\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteShiftAction
{
    public function __construct(
        private EventShiftRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $this->repository->delete(ShiftId::fromString($id));
        return $this->responder->noContent();
    }
}
