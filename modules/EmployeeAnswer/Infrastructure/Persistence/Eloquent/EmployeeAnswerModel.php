<?php
// modules/EmployeeAnswer/Infrastructure/Persistence/Eloquent/EmployeeAnswerModel.php
declare(strict_types=1);

namespace Modules\EmployeeAnswer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Question\Infrastructure\Persistence\Eloquent\QuestionModel;
use Modules\EmployeeQuizAttempt\Infrastructure\Persistence\Eloquent\EmployeeQuizAttemptModel;

final class EmployeeAnswerModel extends Model
{
    use HasUuids;

    protected $table = 'employee_answers';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer_id',
        'is_correct',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(EmployeeQuizAttemptModel::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuestionModel::class, 'question_id');
    }
}
