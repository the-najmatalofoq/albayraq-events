<?php
// modules/EventStaffingPosition/Infrastructure/Persistence/Eloquent/EventStaffingPositionModel.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EventStaffingPositionModel extends Model
{
    use HasUuids;

    protected $table = 'event_staffing_positions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'event_id',
        'title',
        'requirements',
        'quantity',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'requirements' => 'array',
        'quantity' => 'integer',
        'is_active' => 'boolean',
    ];
}
