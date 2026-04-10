<?php
// modules/EventShiftAssignment/Infrastructure/Persistence/Eloquent/EventShiftAssignmentModel.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;
use Modules\EventShift\Infrastructure\Persistence\Eloquent\EventShiftModel;
use Carbon\Carbon;

/**
 * @property string      $id
 * @property string      $participation_id
 * @property string      $shift_id
 * @property string      $status
 * @property Carbon      $created_at
 * @property Carbon|null $updated_at
 */
final class EventShiftAssignmentModel extends Model
{
    use HasUuids;

    protected $table = 'event_shift_assignments';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'participation_id',
        'shift_id',
        'status',
    ];

    public function participation(): BelongsTo
    {
        return $this->belongsTo(EventParticipationModel::class, 'participation_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(EventShiftModel::class, 'shift_id');
    }
}
