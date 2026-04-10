<?php
// modules/EventShiftAssignment/Infrastructure/Persistence/Eloquent/EventShiftAssignmentModel.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventShift\Infrastructure\Persistence\Eloquent\EventShiftModel;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Carbon\Carbon;

/**
 * @property string $id
 * @property string $shift_id
 * @property string $participation_id
 * @property string $status
 * @property string $assigned_by
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventShiftModel $shift
 * @property-read EventParticipationModel $participation
 * @property-read UserModel $assigner
 */
final class EventShiftAssignmentModel extends Model
{
    use HasUuids;

    protected $table = 'event_shift_assignments';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'shift_id',
        'participation_id',
        'status',
        'assigned_by',
        'notes',
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(EventShiftModel::class, 'shift_id');
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(EventParticipationModel::class, 'participation_id');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'assigned_by');
    }
}
