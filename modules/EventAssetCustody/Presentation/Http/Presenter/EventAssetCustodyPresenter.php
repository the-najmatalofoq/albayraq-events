<?php
// modules/EventAssetCustody/Presentation/Http/Presenter/EventAssetCustodyPresenter.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Presentation\Http\Presenter;

use Modules\EventAssetCustody\Domain\EventAssetCustody;
use Modules\EventParticipation\Presentation\Http\Presenter\EventParticipationPresenter;
use Modules\EventParticipation\Domain\EventParticipation;

final class EventAssetCustodyPresenter
{
    public function present(EventAssetCustody $custody, ?EventParticipation $participation = null): array
    {
        return [
            'uuid' => $custody->uuid->value,
            'participation_id' => $custody->participationId->value,
            'item_name' => $custody->itemName->toArray(),
            'description' => $custody->description?->toArray(),
            'status' => $custody->status->value,
            'handed' => EventAssetCustodyActionPresenter::present($custody->handedAt, $custody->handedBy),
            'returned_at' => $custody->returnedAt?->format(DATE_ATOM),
            'participation' => $participation ? EventParticipationPresenter::fromDomain($participation) : null,
        ];
    }

    public function presentCollection(iterable $custodies): array
    {
        $data = [];
        foreach ($custodies as $custody) {
            $data[] = $this->present($custody);
        }
        return $data;
    }
}
