<?php
// filePath: modules/Question/Infrastructure/Persistence/Eloquent/QuestionModel.php
declare(strict_types=1);

namespace Modules\Question\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Quiz\Infrastructure\Persistence\Eloquent\QuizModel;
use Carbon\Carbon;

/**
 * Quiz question model
 *
 * @property string $id
 * @property string $quiz_id
 * @property array $content
 * @property string $type
 * @property array $options
 * @property int $score_weight
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read QuizModel $quiz
 */
final class QuestionModel extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

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

    protected static function newFactory()
    {
        return \Modules\Question\Infrastructure\Persistence\Eloquent\Factories\QuestionFactory::new();
    }
}
