<?php
// modules/EventBreakRequest/Application/Commands/CancelBreak/CancelBreakHandler.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Command\CancelBreak;

use DomainException;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\EventBreakRequest\Domain\Repository\BreakRequestRepositoryInterface;
use Modules\EventBreakRequest\Domain\ValueObject\BreakRequestId;
use Modules\EventBreakRequest\Application\Event\BreakRequestCancelled;

final readonly class CancelBreakHandler
{
    public function __construct(
        private BreakRequestRepositoryInterface $breakRequestRepository,
        private Dispatcher $dispatcher
    ) {}

    public function handle(CancelBreakCommand $command): void
    {
        $breakRequestId = BreakRequestId::fromString($command->breakRequestId);

        $breakRequest = $this->breakRequestRepository->findById($breakRequestId);
        if (!$breakRequest) {
            throw new DomainException("Break request not found.", 404);
        }

        if ($breakRequest->requestedBy->value !== $command->requestedByUserId) {
            throw new DomainException("You can only cancel your own break requests.", 403);
        }

        $breakRequest->cancel();

        $this->breakRequestRepository->save($breakRequest);

        $this->dispatcher->dispatch(new BreakRequestCancelled(
            breakRequestId: $breakRequest->id()->value,
            participationId: $breakRequest->participationId->value
        ));
    }
}
