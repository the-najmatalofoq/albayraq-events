<?php
// modules/EventBreakRequest/Application/Commands/RejectBreak/RejectBreakHandler.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Command\RejectBreak;

use DomainException;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventBreakRequest\Domain\Repository\BreakRequestRepositoryInterface;
use Modules\EventBreakRequest\Domain\ValueObject\BreakRequestId;
use Modules\EventBreakRequest\Application\Event\BreakRequestRejected;

final readonly class RejectBreakHandler
{
    public function __construct(
        private BreakRequestRepositoryInterface $breakRequestRepository,
        private Dispatcher $dispatcher
    ) {}

    public function handle(RejectBreakCommand $command): void
    {
        $breakRequestId = BreakRequestId::fromString($command->breakRequestId);
        $rejectorId = UserId::fromString($command->rejectorId);

        $breakRequest = $this->breakRequestRepository->findById($breakRequestId);
        if (!$breakRequest) {
            throw new DomainException("Break request not found.", 404);
        }

        $breakRequest->reject($rejectorId, $command->reason);

        $this->breakRequestRepository->save($breakRequest);

        $this->dispatcher->dispatch(new BreakRequestRejected(
            breakRequestId: $breakRequest->id()->value,
            participationId: $breakRequest->participationId->value,
            rejectionReason: $command->reason
        ));
    }
}
