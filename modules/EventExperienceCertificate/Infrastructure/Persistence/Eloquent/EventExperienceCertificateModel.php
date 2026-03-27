<?php
// modules/EventExperienceCertificate/Infrastructure/Persistence/Eloquent/EventExperienceCertificateModel.php
declare(strict_types=1);

namespace Modules\EventExperienceCertificate\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EventExperienceCertificateModel extends Model
{
    use HasUuids;

    protected $table = 'event_experience_certificates';

    protected $keyType = 'string';

    public $incrementing = false;

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
            'total_hours' => 'decimal:2',
            'average_score' => 'decimal:1',
            'issued_at' => 'datetime',
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
