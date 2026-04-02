<?php
// modules/Shared/Infrastructure/Persistence/Eloquent/WorkScheduleModel.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
// todo: we must move into its own module,
final class WorkScheduleModel extends Model
{
    use HasUuids;

    protected $table = 'work_schedules';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'schedulable_id',
        'schedulable_type',
        'days_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'days_of_week' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function schedulable(): MorphTo
    {
        return $this->morphTo();
    }
}
