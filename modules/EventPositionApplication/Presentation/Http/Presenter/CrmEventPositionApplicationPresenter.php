<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Presenter/CrmEventPositionApplicationPresenter.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Presenter;

use Modules\EventPositionApplication\Domain\EventPositionApplication;

final readonly class CrmEventPositionApplicationPresenter
{
    public function present(EventPositionApplication $app): array
    {
        return [
            'id' => $app->uuid->value,
            'user_id' => $app->userId->value,
            'position_id' => $app->positionId->value,
            'status' => $app->status->value,
            'status_label' => $app->status->label(),
            'ranking_score' => $app->rankingScore,
            'applied_at' => $app->appliedAt->format(\DateTimeInterface::ATOM),
            'reviewed_at' => $app->reviewedAt?->format(\DateTimeInterface::ATOM),
            'reviewed_by' => $app->reviewedBy?->value,
            'is_deleted' => $app->isDeleted(),
            'deleted_at' => $app->deletedAt?->format(\DateTimeInterface::ATOM),
        ];
    }

    public function presentCollection(iterable $items): array
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = $this->present($item);
        }
        return $result;
    }
}
