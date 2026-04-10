<?php
// modules/EventJoinRequest/Infrastructure/Persistence/Eloquent/EventJoinRequestModel.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent\EventStaffingPositionModel;
use Carbon\Carbon;

/**
 * @property string $id
 * @property string $user_id
 * @property string $event_id
 * @property string $position_id
 * @property string $status
 * @property string|null $rejection_reason
 * @property string|null $reviewed_by
 * @property Carbon|null $reviewed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read UserModel $user
 * @property-read EventModel $event
 * @property-read EventStaffingPositionModel $position
 * @property-read UserModel|null $reviewer
 */
final class EventJoinRequestModel extends Model
{
    use HasUuids;

    protected $table = 'event_join_requests';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'event_id',
        'position_id',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(EventStaffingPositionModel::class, 'position_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'reviewed_by');
    }
}
