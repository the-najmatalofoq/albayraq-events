<?php
// modules/ParticipationEvaluation/Infrastructure/Persistence/Eloquent/ParticipationEvaluationModel.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ParticipationEvaluationModel extends Model
{
    use HasUuids;

    protected $table = 'participation_evaluations';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'event_participation_id',
        'evaluator_id',
        'date',
        'score',
        'notes',
        'is_locked',
        'locked_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'score' => 'decimal:1',
            'is_locked' => 'boolean',
            'locked_at' => 'datetime',
        ];
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(
            \Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel::class,
            'event_participation_id',
        );
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(
            \Modules\User\Infrastructure\Persistence\Eloquent\UserModel::class,
            'evaluator_id',
        );
    }
}
