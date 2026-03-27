<?php
// modules/EventParticipationBadge/Infrastructure/Persistence/Eloquent/EventParticipationBadgeModel.php
declare(strict_types=1);

namespace Modules\EventParticipationBadge\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EventParticipationBadgeModel extends Model
{
    use HasUuids;

    protected $table = 'event_participation_badges';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'event_participation_id',
        'badge_data',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'badge_data' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(
            \Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel::class,
            'event_participation_id',
        );
    }
}
