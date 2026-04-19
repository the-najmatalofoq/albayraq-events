<?php
// modules/EventBreakRequest/Domain/Repository/BreakRequestRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Domain\Repository;

use Modules\EventBreakRequest\Domain\BreakRequest;
use Modules\EventBreakRequest\Domain\ValueObject\BreakRequestId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;

interface BreakRequestRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): BreakRequestId;

    public function save(BreakRequest $request): void;

    public function findById(BreakRequestId $id): ?BreakRequest;

    /** @return Collection<int, BreakRequest> */
    public function getApprovedBreaksForParticipation(ParticipationId $participationId, CarbonInterface $date): Collection;

    public function hasOverlappingApprovedBreak(ParticipationId $participationId, CarbonInterface $start, CarbonInterface $end): bool;
}
