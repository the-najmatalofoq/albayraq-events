<?php
// modules/ViolationType/Infrastructure/Persistence/ViolationTypeReflector.php
declare(strict_types=1);

namespace Modules\ViolationType\Infrastructure\Persistence;

use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Infrastructure\Persistence\Eloquent\ViolationTypeModel;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\ViolationType\Domain\Enum\ViolationSeverityEnum;
use Modules\Event\Domain\ValueObject\EventId;

final class ViolationTypeReflector
{
    public static function fromModel(ViolationTypeModel $model): ViolationType
    {
        return ViolationType::create(
            uuid: ViolationTypeId::fromString($model->id),
            name: TranslatableText::fromArray($model->name),
            defaultDeduction: $model->default_deduction_amount !== null 
                ? new Money((float) $model->default_deduction_amount, $model->default_deduction_currency ?? 'SAR') 
                : null,
            severity: ViolationSeverityEnum::from($model->severity),
            eventId: $model->event_id ? EventId::fromString($model->event_id) : null,
            isActive: (bool) $model->is_active
        );
    }
}
