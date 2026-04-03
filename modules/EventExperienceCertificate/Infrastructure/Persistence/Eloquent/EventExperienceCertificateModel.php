<?php

declare(strict_types=1);

namespace Modules\EventExperienceCertificate\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;
use Carbon\Carbon;

/**
 * Event experience certificate model
 *
 * @property string $id
 * @property string $event_participation_id
 * @property float $total_hours
 * @property float $average_score
 * @property Carbon $issued_at
 * @property string $verification_code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventParticipationModel $participation
 */
final class EventExperienceCertificateModel extends Model
{
    use HasUuids;

    protected $table = 'event_experience_certificates';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_participation_id',
        'total_hours',
        'average_score',
        'issued_at',
        'verification_code',
    ];

    protected function casts(): array
    {
        return [
            'total_hours' => 'float',
            'average_score' => 'float',
            'issued_at' => 'datetime',
        ];
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(EventParticipationModel::class, 'event_participation_id');
    }
}
