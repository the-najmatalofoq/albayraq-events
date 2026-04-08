<?php
// modules/WorkSchedule/Infrastructure/Persistence/Eloquent/WorkScheduleModel.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class WorkScheduleModel extends Model
{
    protected $table = 'work_schedules';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'schedulable_id',
        'schedulable_type',
        'date',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'date'      => 'date',
        'is_active' => 'boolean',
    ];
}
