<?php

declare(strict_types=1);

namespace Modules\EventParticipationBadge\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;

final class EventParticipationBadgeModel extends Model
{
    use HasUuids;

    protected $table = 'event_participation_badges';
    public $incrementing = false;
    protected $keyType = 'string';

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
        return $this->belongsTo(EventParticipationModel::class, 'event_participation_id');
    }
}
