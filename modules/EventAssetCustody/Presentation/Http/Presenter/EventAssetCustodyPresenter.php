<?php
// modules/EventAssetCustody/Presentation/Http/Presenter/EventAssetCustodyPresenter.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Presentation\Http\Presenter;

use Modules\EventAssetCustody\Domain\EventAssetCustody;

final class EventAssetCustodyPresenter
{
    public static function fromDomain(EventAssetCustody $custody): array
    {
        return [
            'id' => $custody->uuid->value,
            'event_participation_id' => $custody->participationId->value,
            'item_name' => $custody->itemName->toArray(),
            'description' => $custody->description?->toArray(),
            'handed_at' => $custody->handedAt->format('Y-m-d H:i:s'),
            'returned_at' => $custody->returnedAt?->format('Y-m-d H:i:s'),
            'status' => $custody->status->value,
            'status_label' => $custody->status->label(),
            'handed_by' => $custody->handedBy->value,
        ];
    }
}
