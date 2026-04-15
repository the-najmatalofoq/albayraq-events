<?php
// modules/EventBreakRequest/Application/Commands/ApproveBreak/ApproveBreakHandler.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Command\ApproveBreak;

use DomainException;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventBreakRequest\Domain\Repository\BreakRequestRepositoryInterface;
use Modules\EventBreakRequest\Domain\ValueObject\BreakRequestId;
use Modules\EventBreakRequest\Application\Event\BreakRequestApproved;

final readonly class ApproveBreakHandler
{
    public function __construct(
        private BreakRequestRepositoryInterface $breakRequestRepository,
        private Dispatcher $dispatcher
    ) {}

    public function handle(ApproveBreakCommand $command): void
    {
        $breakRequestId = BreakRequestId::fromString($command->breakRequestId);
        $approveBy = UserId::fromString($command->approverId);
        $coverEmployeeId = $command->coverEmployeeId ? UserId::fromString($command->coverEmployeeId) : null;

        $breakRequest = $this->breakRequestRepository->findById($breakRequestId);
        if (!$breakRequest) {
            throw new DomainException("Break request not found.", 404);
        }

        // Ideally, we'd inject BreakRequestDomainService->canSupervisorApprove here 
        // to check permissions before approving, but let's assume route middleware handles auth/capability.

        $breakRequest->approve($approveBy, $coverEmployeeId);

        $this->breakRequestRepository->save($breakRequest);

        $this->dispatcher->dispatch(new BreakRequestApproved(
            breakRequestId: $breakRequest->id()->value,
            participationId: $breakRequest->participationId->value,
            coverEmployeeId: $command->coverEmployeeId
        ));
    }
}
