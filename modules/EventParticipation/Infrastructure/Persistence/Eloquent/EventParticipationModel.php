<?php
// modules/EventParticipation/Infrastructure/Persistence/Eloquent/EventParticipationModel.php
declare(strict_types=1);

namespace Modules\EventParticipation\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

final class EventParticipationModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'event_participations';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'event_id',
        'position_id',
        'group_id',
        'employee_number',
        'status',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
}
