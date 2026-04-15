<?php
// modules/EventBreakRequest/Infrastructure/Persistence/Repositories/EloquentBreakRequestRepository.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Persistence\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as GenericCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Modules\EventBreakRequest\Domain\BreakRequest;
use Modules\EventBreakRequest\Domain\BreakRequestStatus;
use Modules\EventBreakRequest\Domain\ValueObject\BreakRequestId;
use Modules\EventBreakRequest\Domain\Repository\BreakRequestRepositoryInterface;
use Modules\EventBreakRequest\Infrastructure\Persistence\Models\BreakRequestModel;
use Modules\EventBreakRequest\Infrastructure\Persistence\BreakRequestReflector;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

final class EloquentBreakRequestRepository implements BreakRequestRepositoryInterface
{
    public function nextIdentity(): BreakRequestId
    {
        return BreakRequestId::generate();
    }

    public function save(BreakRequest $request): void
    {
        BreakRequestModel::query()->updateOrCreate(
            ['id' => $request->uuid->value],
            [
                'event_participation_id' => $request->participationId->value,
                'date'                   => $request->date->format('Y-m-d'),
                'start_time'             => $request->startTime->format('H:i:s'),
                'end_time'               => $request->endTime->format('H:i:s'),
                'duration_minutes'       => $request->durationMinutes,
                'status'                 => $request->status->value,
                'requested_by'           => $request->requestedBy->value,
                'approved_by'            => $request->approvedBy?->value,
                'approved_at'            => $request->approvedAt?->format('Y-m-d H:i:s'),
                'rejection_reason'       => $request->rejectionReason,
                'cover_employee_id'      => $request->coverEmployeeId?->value,
                'created_at'             => $request->createdAt->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function findById(BreakRequestId $id): ?BreakRequest
    {
        $model = BreakRequestModel::find($id->value);
        return $model ? BreakRequestReflector::fromModel($model) : null;
    }

    public function getApprovedBreaksForParticipation(ParticipationId $participationId, CarbonInterface $date): GenericCollection
    {
        $models = BreakRequestModel::where('event_participation_id', $participationId->value)
            ->where('date', $date->format('Y-m-d'))
            ->where('status', BreakRequestStatus::APPROVED->value)
            ->get();

        return $models->map(fn(BreakRequestModel $m) => BreakRequestReflector::fromModel($m));
    }

    public function hasOverlappingApprovedBreak(ParticipationId $participationId, CarbonInterface $start, CarbonInterface $end): bool
    {
        $date = $start->format('Y-m-d');
        $startTime = $start->format('H:i:s');
        $endTime = $end->format('H:i:s');

        return BreakRequestModel::where('event_participation_id', $participationId->value)
            ->where('date', $date)
            ->where('status', BreakRequestStatus::APPROVED->value)
            ->where(function ($q) use ($startTime, $endTime) {
                // Overlap condition logic
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function ($q2) use ($startTime, $endTime) {
                      $q2->where('start_time', '<=', $startTime)
                         ->where('end_time', '>=', $endTime);
                  });
            })->exists();
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = BreakRequestModel::query();
        
        // Example basic filters
        if ($criteria->has('status')) {
            $query->where('status', $criteria->get('status'));
        }
        if ($criteria->has('event_participation_id')) {
            $query->where('event_participation_id', $criteria->get('event_participation_id'));
        }
        
        $sortBy = $criteria->sortBy ?: 'created_at';
        $sortDir = $criteria->sortDirection ?: 'desc';
        $query->orderBy($sortBy, $sortDir);

        $paginator = $query->paginate($perPage);

        $paginator->setCollection(
            $paginator->getCollection()->map(fn(BreakRequestModel $model) => BreakRequestReflector::fromModel($model))
        );

        return $paginator;
    }

    public function all(FilterCriteria $criteria): GenericCollection
    {
        $query = BreakRequestModel::query();
        
        if ($criteria->has('event_participation_id')) {
            $query->where('event_participation_id', $criteria->get('event_participation_id'));
        }

        return $query->get()->map(fn(BreakRequestModel $model) => BreakRequestReflector::fromModel($model));
    }
}
