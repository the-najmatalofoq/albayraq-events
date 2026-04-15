<?php
// modules/EventBreakRequest/Application/Queries/GetEmployeeRemainingBreakTimeHandler.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Query;

use Modules\EventBreakRequest\Domain\ValueObject\BreakRequestId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventBreakRequest\Domain\Repository\BreakRequestRepositoryInterface;
use Carbon\CarbonImmutable;

final readonly class GetEmployeeRemainingBreakTimeHandler
{
    public function __construct(
        private BreakRequestRepositoryInterface $breakRequestRepository
    ) {}

    public function handle(GetEmployeeRemainingBreakTimeQuery $query): int
    {
        $participationId = ParticipationId::fromString($query->participationId);
        $date = new CarbonImmutable($query->date);

        $approvedBreaks = $this->breakRequestRepository->getApprovedBreaksForParticipation($participationId, $date);

        $totalApprovedMinutes = $approvedBreaks->sum('durationMinutes');

        return max(0, 60 - $totalApprovedMinutes);
    }
}
