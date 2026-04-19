<?php
// modules/PenaltyType/Presentation/Http/Presenter/PenaltyTypePresenter.php
declare(strict_types=1);

namespace Modules\PenaltyType\Presentation\Http\Presenter;

use Modules\PenaltyType\Domain\PenaltyType;

final class PenaltyTypePresenter
{
    public static function fromDomain(PenaltyType $penaltyType): array
    {
        return [
            'id'        => $penaltyType->uuid->value,
            'slug'      => $penaltyType->slug,
            'name'      => $penaltyType->name->getFor(),
            'is_active' => $penaltyType->isActive,
        ];
    }
}
