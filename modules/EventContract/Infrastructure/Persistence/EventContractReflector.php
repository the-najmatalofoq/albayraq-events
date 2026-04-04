<?php

declare(strict_types=1);

namespace Modules\EventContract\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\EventContract\Domain\EventContract;
use Modules\EventContract\Domain\Enum\ContractStatusEnum;
use Modules\EventContract\Domain\ValueObject\ContractId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\EventContract\Infrastructure\Persistence\Eloquent\EventContractModel;

final class EventContractReflector
{
    public function fromEntity(EventContract $contract): array
    {
        return [
            'id' => $contract->uuid->value,
            'event_participation_id' => $contract->participationId->value,
            'contract_type' => $contract->contractType,
            'wage_amount' => $contract->wageAmount,
            'terms' => $contract->terms,
            'status' => $contract->status->value,
            'rejection_reason_id' => $contract->rejectionReasonId?->value,
            'rejection_notes' => $contract->rejectionNotes,
            'sent_at' => $contract->sentAt?->format('Y-m-d H:i:s'),
            'accepted_at' => $contract->acceptedAt?->format('Y-m-d H:i:s'),
            'rejected_at' => $contract->rejectedAt?->format('Y-m-d H:i:s'),
            'created_at' => $contract->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    public function toEntity(EventContractModel $model): EventContract
    {
        return EventContract::reconstitute(
            uuid: new ContractId($model->id),
            participationId: new ParticipationId($model->event_participation_id),
            contractType: $model->contract_type,
            wageAmount: (float) $model->wage_amount,
            terms: $model->terms,
            status: ContractStatusEnum::from($model->status),
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            rejectionReasonId: $model->rejection_reason_id ? new ContractRejectionReasonId($model->rejection_reason_id) : null,
            rejectionNotes: $model->rejection_notes,
            sentAt: $model->sent_at ? new DateTimeImmutable($model->sent_at->toDateTimeString()) : null,
            acceptedAt: $model->accepted_at ? new DateTimeImmutable($model->accepted_at->toDateTimeString()) : null,
            rejectedAt: $model->rejected_at ? new DateTimeImmutable($model->rejected_at->toDateTimeString()) : null,
        );
    }
}
