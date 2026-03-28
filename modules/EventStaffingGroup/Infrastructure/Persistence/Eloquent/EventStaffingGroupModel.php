<?php
// modules/EventStaffingGroup/Infrastructure/Persistence/Eloquent/EventStaffingGroupModel.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;

final class EventStaffingGroupModel extends Model
{
    use HasUuids;

    protected $table = 'event_staffing_groups';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'name',
        'color',
        'is_locked',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'is_locked' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(EventParticipationModel::class, 'group_id');
    }
}
