<?php
// modules/EventExperienceCertificate/Infrastructure/Persistence/Eloquent/EventExperienceCertificateModel.php
declare(strict_types=1);

namespace Modules\EventExperienceCertificate\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;

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
