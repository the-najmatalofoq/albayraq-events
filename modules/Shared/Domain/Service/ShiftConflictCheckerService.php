<?php
// modules/Shared/Domain/Service/ShiftConflictCheckerService.php
declare(strict_types=1);
namespace Modules\Shared\Domain\Service;

use Modules\EventShift\Domain\EventShift;

final class ShiftConflictCheckerService
{
    /**
     * @param EventShift[] $userShifts
     * @param EventShift[] $requestedShifts
     */
    public function hasConflict(array $userShifts, array $requestedShifts): bool
    {
        return $this->findConflictingPair($userShifts, $requestedShifts) !== null;
    }

    /**
     * @param EventShift[] $userShifts
     * @param EventShift[] $requestedShifts
     * @return array{existing: EventShift, requested: EventShift}|null
     */
    public function findConflictingPair(array $userShifts, array $requestedShifts): ?array
    {
        foreach ($userShifts as $existing) {
            foreach ($requestedShifts as $requested) {
                if ($this->overlaps($existing, $requested)) {
                    return ['existing' => $existing, 'requested' => $requested];
                }
            }
        }
        return null;
    }

    /**
     * Returns adjacent (non-overlapping) pairs sorted by chronological order.
     * Each pair is [earlier, later] so the caller knows travel direction.
     *
     * @param EventShift[] $userShifts
     * @param EventShift[] $requestedShifts
     * @return array<array{earlier: EventShift, later: EventShift}>
     */
    public function findAdjacentPairs(array $userShifts, array $requestedShifts): array
    {
        $pairs = [];
        foreach ($userShifts as $existing) {
            foreach ($requestedShifts as $requested) {
                if (!$this->overlaps($existing, $requested)) {
                    [$earlier, $later] = $existing->endAt <= $requested->startAt
                        ? [$existing, $requested]
                        : [$requested, $existing];
                    $pairs[] = ['earlier' => $earlier, 'later' => $later];
                }
            }
        }
        return $pairs;
    }

    private function overlaps(EventShift $a, EventShift $b): bool
    {
        return $a->startAt < $b->endAt && $a->endAt > $b->startAt;
    }
}