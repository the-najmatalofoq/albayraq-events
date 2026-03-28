<?php
// modules/Question/Infrastructure/Persistence/Eloquent/QuestionModel.php
declare(strict_types=1);

namespace Modules\Question\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Quiz\Infrastructure\Persistence\Eloquent\QuizModel;

final class QuestionModel extends Model
{
    use HasUuids;

    protected $table = 'questions';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'quiz_id',
        'content',
        'type',
        'options',
        'score_weight',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'options' => 'array',
            'score_weight' => 'integer',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(QuizModel::class, 'quiz_id');
    }
}
