<?php
// modules/Quiz/Infrastructure/Persistence/Eloquent/QuizModel.php
declare(strict_types=1);

namespace Modules\Quiz\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\Question\Infrastructure\Persistence\Eloquent\QuestionModel;

final class QuizModel extends Model
{
    use HasUuids;

    protected $table = 'quizzes';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'passing_score',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'description' => 'array',
            'passing_score' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuestionModel::class, 'quiz_id');
    }
}
