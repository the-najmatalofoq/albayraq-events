<?php
// modules/ContractRejectionReason/Infrastructure/Persistence/ContractRejectionReasonReflector.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Infrastructure\Persistence;

use Modules\ContractRejectionReason\Domain\ContractRejectionReason;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\ContractRejectionReason\Infrastructure\Persistence\Eloquent\ContractRejectionReasonModel;

final class ContractRejectionReasonReflector
{
    public static function fromModel(ContractRejectionReasonModel $model): ContractRejectionReason
    {
        $reflection = new \ReflectionClass(ContractRejectionReason::class);
        $reason = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => ContractRejectionReasonId::fromString($model->id),
            'reason' => TranslatableText::fromArray($model->reason),
            'isActive' => $model->is_active,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($reason, $value);
        }

        return $reason;
    }
}
