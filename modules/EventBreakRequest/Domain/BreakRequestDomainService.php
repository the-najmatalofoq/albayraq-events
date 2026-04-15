<?php
// modules/EventBreakRequest/Domain/BreakRequestDomainService.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Domain;

use Carbon\CarbonImmutable;
use Modules\Event\Domain\Event;
use Modules\EventParticipation\Domain\EventParticipation;
use Modules\EventBreakRequest\Domain\Repository\BreakRequestRepositoryInterface;
use Modules\EventAttendance\Domain\Repository\EventAttendanceRepositoryInterface;
use Modules\EventBreakRequest\Domain\Exceptions\InvalidBreakTimeException;
use Modules\EventBreakRequest\Domain\Exceptions\BreakLimitExceededException;
use Modules\EventBreakRequest\Domain\Exceptions\BreakOverlapException;
use Carbon\Carbon;

final readonly class BreakRequestDomainService
{
    public function __construct(
        private BreakRequestRepositoryInterface $breakRequestRepository,
        private EventAttendanceRepositoryInterface $attendanceRepository
    ) {}

    public function validateBreakTimeSlot(Event $event, CarbonImmutable $date, CarbonImmutable $start, CarbonImmutable $end): void
    {
        // Parse event daily start/end times relative to the requested date
        $eventStart = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' ' . $event->dailyStartTime);
        $eventEnd = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' ' . $event->dailyEndTime);

        // First hour and last hour constraints
        $firstHourEnd = $eventStart->copy()->addHour();
        $lastHourStart = $eventEnd->copy()->subHour();

        if ($start->lt($firstHourEnd)) {
            throw new InvalidBreakTimeException("Cannot request a break during the first hour of the event.");
        }

        if ($end->gt($lastHourStart)) {
            throw new InvalidBreakTimeException("Cannot request a break during the last hour of the event.");
        }
        
        if ($start->lt($eventStart) || $end->gt($eventEnd)) {
            throw new InvalidBreakTimeException("Break timeframe is outside the event working hours.");
        }
    }

    public function validateTotalBreakDuration(EventParticipation $participation, CarbonImmutable $date, int $requestedMinutes): void
    {
        // Allowed limits
        $maxDailyMinutes = 60;
        
        $approvedBreaks = $this->breakRequestRepository->getApprovedBreaksForParticipation($participation->id(), $date);
        
        $totalApprovedMinutes = $approvedBreaks->sum('durationMinutes');
        
        if (($totalApprovedMinutes + $requestedMinutes) > $maxDailyMinutes) {
            throw new BreakLimitExceededException("Total break duration exceeds the daily limit (60 minutes).");
        }
    }

    public function validateNoOverlap(EventParticipation $participation, CarbonImmutable $start, CarbonImmutable $end): void
    {
        if ($this->breakRequestRepository->hasOverlappingApprovedBreak($participation->id(), $start, $end)) {
            throw new BreakOverlapException("This period overlaps with an existing approved break.");
        }
    }

    public function validateAttendanceBeforeBreak(EventParticipation $participation, CarbonImmutable $date): void
    {
        $attendances = $this->attendanceRepository->findByParticipationId($participation->id());
        
        $hasAttendanceToday = false;
        foreach ($attendances as $attendance) {
            if ($attendance->date->format('Y-m-d') === $date->format('Y-m-d') && $attendance->checkInAt) {
                $hasAttendanceToday = true;
                break;
            }
        }
        
        if (!$hasAttendanceToday) {
            throw new Exceptions\BreakRequestException("You must check in before requesting a break.");
        }
    }
}
