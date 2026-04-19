<?php
// modules/EventBreakRequest/Application/Commands/RequestBreak/RequestBreakHandler.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Command\RequestBreak;

use DomainException;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\EventBreakRequest\Domain\BreakRequest;
use Modules\EventBreakRequest\Domain\BreakRequestDomainService;
use Modules\EventBreakRequest\Domain\Repository\BreakRequestRepositoryInterface;
use Modules\EventBreakRequest\Application\Event\BreakRequestCreated;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Event\Domain\Repository\EventRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventParticipation\Domain\Repository\EventParticipationRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final readonly class RequestBreakHandler
{
    public function __construct(
        private BreakRequestRepositoryInterface $breakRequestRepository,
        private EventRepositoryInterface $eventRepository,
        private EventParticipationRepositoryInterface $participationRepository,
        private BreakRequestDomainService $domainService,
        private Dispatcher $dispatcher
    ) {}

    public function handle(RequestBreakCommand $command): string
    {
        $event = $this->eventRepository->findById(EventId::fromString($command->eventId));
        if (!$event) {
            throw new DomainException("Event not found.");
        }

        $participation = $this->participationRepository->findById(ParticipationId::fromString($command->participationId));
        if (!$participation || $participation->eventId->value !== $event->id()->value) {
            throw new DomainException("Participation not valid for this event.");
        }

        if ($participation->userId->value !== $command->requestedByUserId) {
            throw new DomainException("You can only request breaks for your own participation.");
        }

        $durationMinutes = (int) $command->startTime->diffInMinutes($command->endTime);
        if ($durationMinutes <= 0) {
            throw new DomainException("End time must be after start time.");
        }

        $this->domainService->validateBreakTimeSlot($event, $command->date, $command->startTime, $command->endTime);
        $this->domainService->validateTotalBreakDuration($participation, $command->date, $durationMinutes);
        $this->domainService->validateNoOverlap($participation, $command->startTime, $command->endTime);
        $this->domainService->validateAttendanceBeforeBreak($participation, $command->date);

        $breakRequestId = $this->breakRequestRepository->nextIdentity();
        $breakRequest = BreakRequest::request(
            uuid: $breakRequestId,
            participationId: $participation->id(),
            date: $command->date,
            startTime: $command->startTime,
            endTime: $command->endTime,
            requestedBy: UserId::fromString($command->requestedByUserId)
        );

        $this->breakRequestRepository->save($breakRequest);

        $this->dispatcher->dispatch(new BreakRequestCreated(
            breakRequestId: $breakRequestId->value,
            eventId: $event->id()->value,
            participationId: $participation->id()->value
        ));

        return $breakRequestId->value;
    }
}
