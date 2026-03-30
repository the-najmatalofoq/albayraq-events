<?php
declare(strict_types=1);

namespace Modules\EventContract\Infrastructure\Persistence;

use Modules\EventContract\Domain\EventContract;
use Modules\EventContract\Domain\ValueObject\ContractId;
use Modules\EventContract\Domain\Enum\ContractStatusEnum;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\EventContract\Infrastructure\Persistence\Eloquent\EventContractModel;
use DateTimeImmutable;

final class EventContractReflector
{
    public static function fromModel(EventContractModel $model): EventContract
    {
        $reflection = new \ReflectionClass(EventContract::class);
        $contract = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => ContractId::fromString($model->id),
            'participationId' => ParticipationId::fromString($model->event_participation_id),
            'contractType' => $model->contract_type,
            'wageAmount' => (float) $model->wage_amount,
            'terms' => $model->terms,
            'status' => ContractStatusEnum::from($model->status),
            'rejectionReasonId' => $model->rejection_reason_id ? ContractRejectionReasonId::fromString($model->rejection_reason_id) : null,
            'rejectionNotes' => $model->rejection_notes,
            'sentAt' => $model->sent_at ? self::toDateTimeImmutable($model->sent_at) : null,
            'acceptedAt' => $model->accepted_at ? self::toDateTimeImmutable($model->accepted_at) : null,
            'rejectedAt' => $model->rejected_at ? self::toDateTimeImmutable($model->rejected_at) : null,
            'createdAt' => self::toDateTimeImmutable($model->created_at),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($contract, $value);
        }

        return $contract;
    }

    private static function toDateTimeImmutable($date): DateTimeImmutable
    {
        if ($date instanceof DateTimeImmutable) {
            return $date;
        }
        if ($date instanceof \DateTime) {
            return DateTimeImmutable::createFromInterface($date);
        }
        return new DateTimeImmutable($date);
    }
}
