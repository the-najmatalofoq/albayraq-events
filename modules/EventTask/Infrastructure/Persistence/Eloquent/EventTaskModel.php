<?php
// modules/EventTask/Infrastructure/Persistence/Eloquent/EventTaskModel.php
declare(strict_types=1);

namespace Modules\EventTask\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventTask\Domain\Enum\TaskStatusEnum;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\EventStaffingGroup\Infrastructure\Persistence\Eloquent\EventStaffingGroupModel;
use Carbon\Carbon;

/**
 * Event task model
 *
 * @property string $id
 * @property string $event_id
 * @property string $assigned_to
 * @property string|null $group_id
 * @property array $title
 * @property array $description
 * @property Carbon $due_at
 * @property float|null $location_latitude
 * @property float|null $location_longitude
 * @property TaskStatusEnum $status
 * @property string $created_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventModel $event
 * @property-read UserModel $assignee
 * @property-read EventStaffingGroupModel|null $group
 * @property-read UserModel $creator
 */
final class EventTaskModel extends Model
{
    use HasUuids;

    protected $table = 'event_tasks';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'assigned_to',
        'group_id',
        'title',
        'description',
        'due_at',
        'location_latitude',
        'location_longitude',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'description' => 'array',
            'due_at' => 'datetime',
            'location_latitude' => 'float',
            'location_longitude' => 'float',
            'status' => TaskStatusEnum::class,
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'assigned_to');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(EventStaffingGroupModel::class, 'group_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'created_by');
    }
}
