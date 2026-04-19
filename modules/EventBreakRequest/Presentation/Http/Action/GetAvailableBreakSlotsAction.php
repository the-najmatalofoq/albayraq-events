<?php
// modules/EventBreakRequest/Presentation/Http/Controllers/Mobile/GetAvailableBreakSlotsAction.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Action;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Event\Domain\Repository\EventRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventParticipation\Domain\Repository\EventParticipationRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventBreakRequest\Domain\Repository\BreakRequestRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

final readonly class GetAvailableBreakSlotsAction
{
    public function __construct(
        private EventRepositoryInterface $eventRepository,
        private EventParticipationRepositoryInterface $participationRepository,
        private BreakRequestRepositoryInterface $breakRequestRepository,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $eventId = $request->query('event_id');
        $participationId = $request->query('participation_id');
        $dateQuery = $request->query('date', Carbon::today()->format('Y-m-d'));

        if (!$eventId || !$participationId) {
            return $this->responder->error('MISSING_DATA', 400, 'event_id and participation_id are required');
        }

        $event = $this->eventRepository->findById(EventId::fromString($eventId));
        if (!$event) {
            return $this->responder->error('NOT_FOUND', 404, 'Event not found');
        }

        $participation = $this->participationRepository->findById(ParticipationId::fromString($participationId));
        if (!$participation) {
            return $this->responder->error('NOT_FOUND', 404, 'Participation not found');
        }

        // Logic to calculate available 1-hour slots
        $eventStart = Carbon::createFromFormat('Y-m-d H:i:s', $dateQuery . ' ' . $event->dailyStartTime);
        $eventEnd = Carbon::createFromFormat('Y-m-d H:i:s', $dateQuery . ' ' . $event->dailyEndTime);

        $firstHourEnd = $eventStart->copy()->addHour();
        $lastHourStart = $eventEnd->copy()->subHour();

        $availableSlots = [];
        $currentStart = $firstHourEnd->copy();

        while ($currentStart->copy()->addHour()->lte($lastHourStart)) {
            $currentEnd = $currentStart->copy()->addHour();

            $hasOverlap = $this->breakRequestRepository->hasOverlappingApprovedBreak(
                $participation->id(),
                CarbonImmutable::instance($currentStart),
                CarbonImmutable::instance($currentEnd)
            );

            if (!$hasOverlap) {
                $availableSlots[] = [
                    'start_time' => $currentStart->format('H:i:s'),
                    'end_time' => $currentEnd->format('H:i:s'),
                ];
            }

            // Move by 30 mins or 1 hour step
            $currentStart->addHour();
        }

        return $this->responder->success(data: ['available_slots' => $availableSlots]);
    }
}
