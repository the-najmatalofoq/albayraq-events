<?php
// modules/EventStaffingPositionRequirement/Infrastructure/Persistence/Eloquent/EventStaffingPositionRequirementModel.php
declare(strict_types=1);

namespace Modules\EventStaffingPositionRequirement\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EventStaffingPositionRequirementModel extends Model
{
    use HasUuids;

    protected $table = 'event_staffing_position_requirements';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'position_id',
        'title',
        'is_required',
        'description',
    ];

    protected $casts = [
        'title' => 'array',
        'is_required' => 'boolean',
    ];
}
