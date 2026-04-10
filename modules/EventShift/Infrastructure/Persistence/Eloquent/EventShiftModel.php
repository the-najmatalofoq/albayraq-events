<?php
// modules/EventShift/Infrastructure/Persistence/Eloquent/EventShiftModel.php
declare(strict_types=1);

namespace Modules\EventShift\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent\EventStaffingPositionModel;
use Modules\EventShiftAssignment\Infrastructure\Persistence\Eloquent\EventShiftAssignmentModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property string $id
 * @property string $event_id
 * @property string $position_id
 * @property string $label
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property int|null $max_assignees
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventModel $event
 * @property-read EventStaffingPositionModel $position
 * @property-read Collection|EventShiftAssignmentModel[] $assignments
 */
final class EventShiftModel extends Model
{
    use HasUuids;

    protected $table = 'event_shifts';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'position_id',
        'label',
        'start_at',
        'end_at',
        'max_assignees',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'max_assignees' => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(EventStaffingPositionModel::class, 'position_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(EventShiftAssignmentModel::class, 'shift_id');
    }
}
