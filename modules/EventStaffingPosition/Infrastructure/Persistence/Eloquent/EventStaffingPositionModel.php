<?php
// modules/EventStaffingPosition/Infrastructure/Persistence/Eloquent/EventStaffingPositionModel.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;

final class EventStaffingPositionModel extends Model
{
    use HasUuids;

    protected $table = 'event_staffing_positions';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'title',
        'wage_amount',
        'wage_type',
        'headcount',
        'requirements',
        'is_announced',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'requirements' => 'array',
            'wage_amount' => 'decimal:2',
            'headcount' => 'integer',
            'is_announced' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(EventParticipationModel::class, 'position_id');
    }
}
