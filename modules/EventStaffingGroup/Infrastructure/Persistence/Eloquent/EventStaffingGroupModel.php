<?php
// modules/EventStaffingGroup/Infrastructure/Persistence/Eloquent/EventStaffingGroupModel.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EventStaffingGroupModel extends Model
{
    use HasUuids;

    protected $table = 'event_staffing_groups';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'event_id',
        'name',
        'leader_id',
        'color',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];
}
