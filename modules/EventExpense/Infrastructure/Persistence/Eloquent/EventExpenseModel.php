<?php
// modules/EventExpense/Infrastructure/Persistence/Eloquent/EventExpenseModel.php
declare(strict_types=1);

namespace Modules\EventExpense\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

final class EventExpenseModel extends Model
{
    use HasUuids, HasTranslations;

    protected $table = 'event_expenses';

    protected $keyType = 'string';

    public $incrementing = false;

    public array $translatable = ['description'];

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
            'approved_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(
            \Modules\Event\Infrastructure\Persistence\Eloquent\EventModel::class,
            'event_id',
        );
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(
            \Modules\User\Infrastructure\Persistence\Eloquent\UserModel::class,
            'submitted_by',
        );
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            \Modules\User\Infrastructure\Persistence\Eloquent\UserModel::class,
            'approved_by',
        );
    }
}
