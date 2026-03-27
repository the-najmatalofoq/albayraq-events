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
                'violation_type_id' => $violation->violationTypeId->value,
                'description' => $violation->description->toArray(),
                'issued_by' => $violation->issuedBy->value,
                'occurred_at' => $violation->occurredAt->format('Y-m-d H:i:s'),
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
}
