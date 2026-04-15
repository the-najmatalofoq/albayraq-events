<?php
// modules/ViolationType/Presentation/Http/Presenter/ViolationTypePresenter.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Presenter;

use Modules\ViolationType\Domain\ViolationType;

final class ViolationTypePresenter
{
    public static function fromDomain(ViolationType $violationType): array
    {
        return [
            'id'        => $violationType->uuid->value,
            'slug'      => $violationType->slug,
            'name'      => $violationType->name->getFor(),
            'is_active' => $violationType->isActive,
        ];
    }
}
