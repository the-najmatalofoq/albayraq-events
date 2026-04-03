<?php
// modules/EventExpense/Infrastructure/Persistence/Eloquent/EventExpenseModel.php
declare(strict_types=1);

namespace Modules\EventExpense\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventExpense\Domain\Enum\ExpenseStatusEnum;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Carbon\Carbon;

/**
 * Event expense model
 *
 * @property string $id
 * @property string $event_id
 * @property array $description
 * @property float $amount
 * @property string $category
 * @property ExpenseStatusEnum $status
 * @property string $submitted_by
 * @property string|null $approved_by
 * @property Carbon|null $approved_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventModel $event
 * @property-read UserModel $submitter
 * @property-read UserModel|null $approver
 */
final class EventExpenseModel extends Model
{
    use HasUuids;

    protected $table = 'event_expenses';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'description',
        'amount',
        'category',
        'status',
        'submitted_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'description' => 'array',
            'amount' => 'decimal:2',
            'status' => ExpenseStatusEnum::class,
            'approved_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'submitted_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'approved_by');
    }
}
