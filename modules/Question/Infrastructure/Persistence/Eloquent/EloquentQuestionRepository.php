<?php
// filePath: modules/Question/Infrastructure/Persistence/Eloquent/EloquentQuestionRepository.php
declare(strict_types=1);

namespace Modules\Question\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Question\Domain\Question;
use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\Question\Infrastructure\Persistence\QuestionReflector;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

final class EloquentQuestionRepository implements QuestionRepositoryInterface
{
    public function nextIdentity(): QuestionId
    {
        return QuestionId::generate();
    }

    public function findById(QuestionId $id): ?Question
    {
        $model = QuestionModel::find($id->value);
        return $model ? QuestionReflector::fromModel($model) : null;
    }

    public function findByIdWithTrashed(QuestionId $id): ?Question
    {
        $model = QuestionModel::withTrashed()->find($id->value);
        return $model ? QuestionReflector::fromModel($model) : null;
    }

    public function save(Question $question): void
    {
        QuestionModel::updateOrCreate(
            ['id' => $question->uuid->value],
            [
                'quiz_id' => $question->quizId->value,
                'content' => $question->content->toArray(),
                'type' => $question->type,
                'options' => $question->options,
                'score_weight' => $question->scoreWeight,
                'deleted_at' => $question->deletedAt?->format('Y-m-d H:i:s'),
            ],
        );
    }

    public function delete(QuestionId $id): void
    {
        QuestionModel::destroy($id->value);
    }

    public function hardDelete(QuestionId $id): void
    {
        QuestionModel::withTrashed()->where('id', $id->value)->forceDelete();
    }

    public function restore(QuestionId $id): void
    {
        QuestionModel::withTrashed()->where('id', $id->value)->restore();
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->applyFilters(QuestionModel::query(), $criteria);

        return $query->paginate($perPage);
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = $this->applyFilters(QuestionModel::query(), $criteria);

        return $query->get()->map(fn($m) => QuestionReflector::fromModel($m));
    }

    private function applyFilters(Builder $query, FilterCriteria $criteria): Builder
    {
        if ($criteria->has('quiz_id')) {
            $query->where('quiz_id', $criteria->get('quiz_id'));
        }

        if ($criteria->has('type')) {
            $query->where('type', $criteria->get('type'));
        }

        if ($criteria->has('search')) {
            $query->where('content', 'like', "%{$criteria->get('search')}%");
        }

        if ($criteria->get('trashed') === 'only') {
            $query->onlyTrashed();
        } elseif ($criteria->get('trashed') === 'with') {
            $query->withTrashed();
        }

        if ($criteria->sortBy) {
            $query->orderBy($criteria->sortBy, $criteria->sortDirection);
        }

        return $query;
    }
}
