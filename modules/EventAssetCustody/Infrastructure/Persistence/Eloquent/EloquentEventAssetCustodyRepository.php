<?php
// modules/EventAssetCustody/Infrastructure/Persistence/Eloquent/EloquentEventAssetCustodyRepository.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Infrastructure\Persistence\Eloquent;

use Modules\EventAssetCustody\Domain\EventAssetCustody;
use Modules\EventAssetCustody\Domain\ValueObject\CustodyId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventAssetCustody\Domain\Repository\EventAssetCustodyRepositoryInterface;
use Modules\EventAssetCustody\Infrastructure\Persistence\EventAssetCustodyReflector;

final class EloquentEventAssetCustodyRepository implements EventAssetCustodyRepositoryInterface
{
    public function nextIdentity(): CustodyId
    {
        return CustodyId::generate();
    }

    public function save(EventAssetCustody $custody): void
    {
        EventAssetCustodyModel::updateOrCreate(
            ['id' => $custody->uuid->value],
            [
                'event_participation_id' => $custody->participationId->value,
                'item_name' => $custody->itemName->toArray(),
                'description' => $custody->description?->toArray(),
                'status' => $custody->status->value,
                'handed_at' => $custody->handedAt->format('Y-m-d H:i:s'),
                'returned_at' => $custody->returnedAt?->format('Y-m-d H:i:s'),
                'handed_by' => $custody->handedBy->value,
            ]
        );
    }

    public function findById(CustodyId $id): ?EventAssetCustody
    {
        $model = EventAssetCustodyModel::find($id->value);
        return $model ? EventAssetCustodyReflector::fromModel($model) : null;
    }

    public function findByParticipationId(ParticipationId $participationId): array
    {
        return EventAssetCustodyModel::where('event_participation_id', $participationId->value)
            ->get()
            ->map(function (EventAssetCustodyModel $model) {
                return EventAssetCustodyReflector::fromModel($model);
            })
            ->toArray();
    }
}
