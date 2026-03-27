<?php
// modules/ParticipationViolation/Infrastructure/Persistence/Eloquent/ParticipationViolationModel.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class ParticipationViolationModel extends Model
{
    use HasUuids;

    protected $table = 'participation_violations';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'event_participation_id',
        'violation_type_id',
        'description',
        'issued_by',
        'occurred_at',
    ];

    protected $casts = [
        'description' => 'array',
        'occurred_at' => 'datetime',
    ];
}
