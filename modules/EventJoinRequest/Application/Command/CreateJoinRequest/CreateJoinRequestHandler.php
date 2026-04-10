<?php
// modules/EventJoinRequest/Application/Command/CreateJoinRequest/CreateJoinRequestHandler.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Application\Command\CreateJoinRequest;

use Modules\EventJoinRequest\Application\Command\CreateJoinRequest\CreateJoinRequestCommand;
use Modules\EventJoinRequest\Domain\EventJoinRequest;
use Modules\EventJoinRequest\Domain\Repository\EventJoinRequestRepositoryInterface;
use Modules\EventJoinRequest\Domain\Exception\DuplicateJoinRequestException;
use Modules\EventJoinRequest\Domain\Exception\ShiftConflictException;
use Modules\EventJoinRequest\Domain\Exception\GeoFeasibilityException;
use Modules\EventJoinRequest\Domain\ValueObject\JoinRequestId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Event\Domain\Repository\EventRepositoryInterface;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\Shared\Domain\Service\ShiftConflictCheckerService;
use Modules\Shared\Domain\Service\GeoFeasibilityService;

final readonly class CreateJoinRequestHandler
{
    public function __construct(
        private EventJoinRequestRepositoryInterface $repository,
        private EventShiftRepositoryInterface $shiftRepository,
        private EventRepositoryInterface $eventRepository,
        private ShiftConflictCheckerService $conflictService,
        private GeoFeasibilityService $geoService,
    ) {
    }

    public function handle(CreateJoinRequestCommand $command): JoinRequestId
    {
        $userId = UserId::fromString($command->userId);
        $eventId = EventId::fromString($command->eventId);
        $positionId = PositionId::fromString($command->positionId);

        $existing = $this->repository->findByUserAndEvent($userId, $eventId);
        if ($existing !== null) {
            throw DuplicateJoinRequestException::create($command->userId, $command->eventId);
        }

        $userShifts = $this->shiftRepository->findActiveByUserId($userId);
        $requestedShifts = $this->shiftRepository->findByEventAndPosition($eventId, $positionId);

        if (count($userShifts) > 0 && count($requestedShifts) > 0) {
            $conflictingPair = $this->conflictService->findConflictingPair($userShifts, $requestedShifts);

            if ($conflictingPair !== null) {
                [$userShift, $reqShift] = $conflictingPair;

                $fromEvent = $this->eventRepository->findById($userShift->eventId);
                $toEvent = $this->eventRepository->findById($reqShift->eventId);

                if ($fromEvent && $toEvent && !$this->geoService->isFeasible($fromEvent, $toEvent, $userShift, $reqShift)) {
                    throw GeoFeasibilityException::create(
                        $fromEvent->uuid->value,
                        $toEvent->uuid->value
                    );
                }

                throw ShiftConflictException::create(
                    "Overlap between existing shift {$userShift->label} and requested {$reqShift->label}"
                );
            }
        }

        $id = $this->repository->nextIdentity();
        $request = EventJoinRequest::create($id, $userId, $eventId, $positionId);
        $this->repository->save($request);

        return $id;
    }
}
