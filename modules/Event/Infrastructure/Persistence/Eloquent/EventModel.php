<?php
// modules/Event/Infrastructure/Persistence/Eloquent/EventModel.php
declare(strict_types=1);

namespace Modules\Event\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Infrastructure\Persistence\Eloquent\UserModel;
use Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent\EventStaffingPositionModel;
use Modules\EventStaffingGroup\Infrastructure\Persistence\Eloquent\EventStaffingGroupModel;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;

final class EventModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'events';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
        'geofence_radius',
        'address',
        'start_date',
        'end_date',
        'daily_start_time',
        'daily_end_time',
        'employment_terms',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'description' => 'array',
            'address' => 'array',
            'employment_terms' => 'array',
            'latitude' => 'float',
            'longitude' => 'float',
            'geofence_radius' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'created_by');
    }

    public function staffingPositions(): HasMany
    {
        return $this->hasMany(EventStaffingPositionModel::class, 'event_id');
    }

    public function staffingGroups(): HasMany
    {
        return $this->hasMany(EventStaffingGroupModel::class, 'event_id');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(EventParticipationModel::class, 'event_id');
    }
}
