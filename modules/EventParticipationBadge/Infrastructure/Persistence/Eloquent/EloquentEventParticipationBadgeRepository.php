<?php

namespace Modules\EventParticipationBadge\Infrastructure\Persistence\Eloquent;

use DateTimeImmutable;
use Modules\EventParticipationBadge\Domain\EventParticipationBadge;
use Modules\EventParticipationBadge\Domain\Repository\EventParticipationBadgeRepositoryInterface;

class EloquentEventParticipationBadgeRepository implements EventParticipationBadgeRepositoryInterface
{
    public function findById(string $id): ?EventParticipationBadge
    {
        $model = EventParticipationBadgeModel::find($id);
        if (!$model) {
            return null;
        }

        return new EventParticipationBadge(
            $model->id,
            $model->event_participation_id,
            $model->badge_data,
            $model->generated_at ? DateTimeImmutable::createFromMutable($model->generated_at) : null,
        );
    }

    public function save(EventParticipationBadge $badge): void
    {
        $model = EventParticipationBadgeModel::findOrNew($badge->id);
        $model->event_participation_id = $badge->eventParticipationId;
        $model->badge_data = $badge->badgeData;
        $model->generated_at = $badge->generatedAt?->format('Y-m-d H:i:s');
        $model->save();
    }
}
