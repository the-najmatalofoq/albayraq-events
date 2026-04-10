<?php
// modules/EventJoinRequest/Application/Command/ReviewJoinRequest/ReviewJoinRequestHandler.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Application\Command\ReviewJoinRequest;

use Modules\EventJoinRequest\Domain\Repository\EventJoinRequestRepositoryInterface;
use Modules\EventJoinRequest\Domain\Exception\JoinRequestNotFoundException;
use Modules\EventJoinRequest\Domain\Exception\JoinRequestAlreadyReviewedException;
use Modules\EventJoinRequest\Domain\ValueObject\JoinRequestId;
use Modules\EventParticipation\Domain\EventParticipation;
use Modules\EventParticipation\Domain\Repository\EventParticipationRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\User\Domain\ValueObject\UserId;

final readonly class ReviewJoinRequestHandler
{
    public function __construct(
        private EventJoinRequestRepositoryInterface $joinRequestRepository,
        private EventParticipationRepositoryInterface $participationRepository,
    ) {}

    public function handle(ReviewJoinRequestCommand $command): void
    {
        $request = $this->joinRequestRepository->findById(JoinRequestId::fromString($command->joinRequestId));

        if ($request === null) {
            throw JoinRequestNotFoundException::create($command->joinRequestId);
        }

        if (!$request->isPending()) {
            throw JoinRequestAlreadyReviewedException::create($command->joinRequestId);
        }

        $reviewerId = UserId::fromString($command->reviewerId);

        if ($command->approved) {
            $request->approve($reviewerId);
            $this->joinRequestRepository->save($request);

            $participationId = $this->participationRepository->nextIdentity();
            $participation = EventParticipation::create(
                uuid: $participationId,
                userId: $request->userId,
                eventId: $request->eventId,
                positionId: $request->positionId,
            );
            $this->participationRepository->save($participation);
        } else {
            $request->reject($reviewerId, $command->rejectionReason ?? '');
            $this->joinRequestRepository->save($request);
        }
    }
}
