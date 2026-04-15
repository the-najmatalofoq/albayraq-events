<?php
// modules/DeductionType/Presentation/Http/Presenter/DeductionTypePresenter.php
declare(strict_types=1);

namespace Modules\DeductionType\Presentation\Http\Presenter;

use Modules\DeductionType\Domain\DeductionType;

final class DeductionTypePresenter
{
    public static function fromDomain(DeductionType $deductionType): array
    {
        return [
            'id'        => $deductionType->uuid->value,
            'slug'      => $deductionType->slug,
            'name'      => $deductionType->name->getFor(),
            'is_active' => $deductionType->isActive,
        ];
    }
}
