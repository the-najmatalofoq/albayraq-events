<?php
// modules/EventBreakRequest/Infrastructure/Persistence/Models/BreakRequestModel.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;

class BreakRequestModel extends Model
{
    use SoftDeletes;

    protected $table = 'break_requests';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'event_participation_id',
        'date',
        'start_time',
        'end_time',
        'duration_minutes',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'cover_employee_id',
    ];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function participation(): BelongsTo
    {
        return $this->belongsTo(EventParticipationModel::class, 'event_participation_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'approved_by');
    }

    public function coverEmployee(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'cover_employee_id');
    }
}
