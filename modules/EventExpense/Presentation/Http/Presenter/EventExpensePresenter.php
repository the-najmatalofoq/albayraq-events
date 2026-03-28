<?php
// modules/EventExpense/Presentation/Http/Presenter/EventExpensePresenter.php
declare(strict_types=1);

namespace Modules\EventExpense\Presentation\Http\Presenter;

use Modules\EventExpense\Domain\EventExpense;

final class EventExpensePresenter
{
    public function present(EventExpense $expense): array
    {
        return [
            'uuid'          => $expense->uuid->value(),
            'event_id'       => $expense->eventId->value(),
            'description'   => $expense->description->toArray(),
            'amount'        => $expense->amount,
            'category'      => $expense->category,
            'status'        => $expense->status->value,
            'submission'    => [
                'at' => $expense->createdAt->format(DATE_ATOM),
                'by' => $expense->submittedBy->value(),
            ],
            'approval'      => [
                'at' => $expense->approvedAt?->format(DATE_ATOM),
                'by' => $expense->approvedBy?->value(),
            ],
        ];
    }

    public function presentCollection(iterable $expenses): array
    {
        $data = [];
        foreach ($expenses as $expense) {
            $data[] = $this->present($expense);
        }
        return $data;
    }
}
