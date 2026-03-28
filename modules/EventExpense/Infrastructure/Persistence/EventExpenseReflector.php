<?php
// modules/EventExpense/Infrastructure/Persistence/EventExpenseReflector.php
declare(strict_types=1);

namespace Modules\EventExpense\Infrastructure\Persistence;

use Modules\EventExpense\Domain\EventExpense;
use Modules\EventExpense\Domain\ValueObject\ExpenseId;
use Modules\EventExpense\Domain\Enum\ExpenseStatusEnum;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventExpense\Infrastructure\Persistence\Eloquent\EventExpenseModel;
use DateTimeImmutable;

final class EventExpenseReflector
{
    public static function fromModel(EventExpenseModel $model): EventExpense
    {
        $reflection = new \ReflectionClass(EventExpense::class);
        $expense = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'          => ExpenseId::fromString($model->id),
            'eventId'       => EventId::fromString($model->event_id),
            'description'   => TranslatableText::fromArray($model->description),
            'amount'        => (float) $model->amount,
            'category'      => $model->category,
            'status'        => ExpenseStatusEnum::from($model->status),
            'submittedBy'   => UserId::fromString($model->submitted_by),
            'createdAt'     => $model->created_at->toDateTimeImmutable(),
            'approvedBy'    => $model->approved_by ? UserId::fromString($model->approved_by) : null,
            'approvedAt'    => $model->approved_at ? DateTimeImmutable::createFromInterface($model->approved_at) : null,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($expense, $value);
        }

        return $expense;
    }
}
