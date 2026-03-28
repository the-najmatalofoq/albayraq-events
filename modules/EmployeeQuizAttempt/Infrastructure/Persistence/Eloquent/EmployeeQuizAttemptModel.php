<?php
// modules/EmployeeQuizAttempt/Infrastructure/Persistence/Eloquent/EmployeeQuizAttemptModel.php
declare(strict_types=1);

namespace Modules\EmployeeQuizAttempt\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Quiz\Infrastructure\Persistence\Eloquent\QuizModel;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;
use Modules\EmployeeAnswer\Infrastructure\Persistence\Eloquent\EmployeeAnswerModel;

final class EmployeeQuizAttemptModel extends Model
{
    use HasUuids;

    protected $table = 'employee_quiz_attempts';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'quiz_id',
        'event_participation_id',
        'score',
        'status',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(QuizModel::class, 'quiz_id');
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(EventParticipationModel::class, 'event_participation_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EmployeeAnswerModel::class, 'attempt_id');
    }
}
