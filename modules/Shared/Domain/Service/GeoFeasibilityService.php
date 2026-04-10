<?php
// modules/Shared/Domain/Service/GeoFeasibilityService.php
declare(strict_types=1);
namespace Modules\Shared\Domain\Service;

use Modules\Event\Domain\Event;
use Modules\EventShift\Domain\EventShift;

final class GeoFeasibilityService
{
    private const EARTH_RADIUS_KM = 6371.0;
    private const ASSUMED_SPEED_KMH = 60.0;

    /**
     * Checks whether a user can travel from $fromEvent to $toEvent
     * between $fromShift ending and $toShift starting.
     * Uses circle-edge-to-circle-edge distance (geofence radius aware).
     */
    public function isFeasible(
        Event $fromEvent,
        Event $toEvent,
        EventShift $fromShift,
        EventShift $toShift,
    ): bool {
        $gapSeconds = $toShift->startAt->getTimestamp() - $fromShift->endAt->getTimestamp();

        if ($gapSeconds <= 0) {
            return false;
        }

        // Same event — no travel required
        if ($fromEvent->uuid->value === $toEvent->uuid->value) {
            return true;
        }

        $distanceKm = $this->edgeToEdgeDistance($fromEvent, $toEvent);
        $requiredSeconds = ($distanceKm / self::ASSUMED_SPEED_KMH) * 3600;

        return $gapSeconds >= $requiredSeconds;
    }

    /**
     * Minimum distance between the perimeters of two geofence circles.
     * Returns 0 when circles overlap or are adjacent.
     */
    private function edgeToEdgeDistance(Event $a, Event $b): float
    {
        $centerKm = $this->haversine($a->latitude, $a->longitude, $b->latitude, $b->longitude);
        $radiusAKm = $a->geofenceRadius / 1000.0;
        $radiusBKm = $b->geofenceRadius / 1000.0;

        return max(0.0, $centerKm - $radiusAKm - $radiusBKm);
    }

    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return 2 * self::EARTH_RADIUS_KM * asin(sqrt($a));
    }
}