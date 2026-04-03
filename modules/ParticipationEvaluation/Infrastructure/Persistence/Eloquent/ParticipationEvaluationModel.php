<?php
// modules/ParticipationEvaluation/Infrastructure/Persistence/Eloquent/ParticipationEvaluationModel.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;
use Carbon\Carbon;

/**
 * Participation evaluation model
 *
 * @property string $id
 * @property string $event_participation_id
 * @property string $evaluator_id
 * @property Carbon $date
 * @property float $score
 * @property string|null $notes
 * @property bool $is_locked
 * @property Carbon|null $locked_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventParticipationModel $participation
 * @property-read UserModel $evaluator
 */
final class ParticipationEvaluationModel extends Model
{
    use HasUuids;

    protected $table = 'participation_evaluations';
    public $incrementing = false;
    protected $keyType = 'string';

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
            'score' => 'float',
            'is_locked' => 'boolean',
            'locked_at' => 'datetime',
        ];
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(EventParticipationModel::class, 'event_participation_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'evaluator_id');
    }
}
