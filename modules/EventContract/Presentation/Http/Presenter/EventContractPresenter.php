<?php
// modules/EventContract/Presentation/Http/Presenter/EventContractPresenter.php
declare(strict_types=1);

namespace Modules\EventContract\Presentation\Http\Presenter;

use Modules\EventContract\Domain\EventContract;

final class EventContractPresenter
{
    public function present(EventContract $contract): array
    {
        return [
            'uuid' => $contract->uuid->value,
            'participation_id' => $contract->participationId->value,
            'contract_type' => $contract->contractType,
            'wage_amount' => $contract->wageAmount,
            'terms' => $contract->terms,
            'status' => $contract->status->value,
            'rejection' => EventContractRejectionPresenter::present($contract),
            'sent_at' => $contract->sentAt?->format(DATE_ATOM),
            'accepted_at' => $contract->acceptedAt?->format(DATE_ATOM),
            'rejected_at' => $contract->rejectedAt?->format(DATE_ATOM),
            'created_at' => $contract->createdAt->format(DATE_ATOM),
        ];
    }

    public function presentCollection(iterable $contracts): array
    {
        $data = [];
        foreach ($contracts as $contract) {
            $data[] = $this->present($contract);
        }
        return $data;
    }
}
