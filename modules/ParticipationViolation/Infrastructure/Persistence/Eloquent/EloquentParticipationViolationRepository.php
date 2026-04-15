<?php
// modules/ParticipationViolation/Infrastructure/Persistence/Eloquent/EloquentParticipationViolationRepository.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Infrastructure\Persistence\Eloquent;

use Modules\ParticipationViolation\Domain\ParticipationViolation;
use Modules\ParticipationViolation\Domain\ValueObject\ViolationId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ParticipationViolation\Domain\Repository\ParticipationViolationRepositoryInterface;
use Modules\ParticipationViolation\Infrastructure\Persistence\ParticipationViolationReflector;

final class EloquentParticipationViolationRepository implements ParticipationViolationRepositoryInterface
{
    public function nextIdentity(): ViolationId
    {
        return ViolationId::generate();
    }

    public function save(ParticipationViolation $violation): void
    {
        ParticipationViolationModel::updateOrCreate(
            ['id' => $violation->uuid->value],
            [
                'event_participation_id' => $violation->participationId->value,
                'violation_type_id'      => $violation->violationTypeId->value,
                'deduction_type_id'      => $violation->deductionTypeId?->value,
                'penalty_type_id'        => $violation->penaltyTypeId?->value,
                'description'            => $violation->description,
                'reported_by'            => $violation->reportedBy->value,
                'date'                   => $violation->date->format('Y-m-d'),
            ]
        );
    }

    public function findById(ViolationId $id): ?ParticipationViolation
    {
        $model = ParticipationViolationModel::find($id->value);
        return $model ? ParticipationViolationReflector::fromModel($model) : null;
    }

    public function findByParticipationId(ParticipationId $participationId): array
    {
        return ParticipationViolationModel::where('event_participation_id', $participationId->value)
            ->get()
            ->map(fn(ParticipationViolationModel $m) => ParticipationViolationReflector::fromModel($m))
            ->toArray();
    }

    public function paginate(\Modules\Shared\Domain\ValueObject\FilterCriteria $criteria, int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = ParticipationViolationModel::query();
        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->setCollection(
            $paginator->getCollection()->map(fn(ParticipationViolationModel $model) => ParticipationViolationReflector::fromModel($model))
        );

        return $paginator;
    }

    public function all(\Modules\Shared\Domain\ValueObject\FilterCriteria $criteria): \Illuminate\Support\Collection
    {
        $query = ParticipationViolationModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(ParticipationViolationModel $model) => ParticipationViolationReflector::fromModel($model));
    }

    public function delete(ViolationId $id): void
    {
        ParticipationViolationModel::query()->where('id', $id->value)->delete();
    }

    private function applyCriteria($query, \Modules\Shared\Domain\ValueObject\FilterCriteria $criteria): void
    {
        if ($criteria->search) {
            $query->where(function ($q) use ($criteria) {
                $q->where('description', 'like', "%{$criteria->search}%");
            });
        }

        if ($criteria->has('participation_id')) {
            $query->where('event_participation_id', $criteria->get('participation_id'));
        }

        if ($criteria->has('status')) {
            $query->where('status', $criteria->get('status'));
        }

        $sortBy = $criteria->sortBy ?: 'created_at';
        $sortDir = $criteria->sortDirection ?: 'desc';
        $query->orderBy($sortBy, $sortDir);
    }
}
