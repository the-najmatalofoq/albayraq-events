<?php
// filePath: modules/Question/Application/Handlers/Crm/CrmListQuestionsHandler.php
declare(strict_types=1);

namespace Modules\Question\Application\Handlers\Crm;

use Illuminate\Support\Collection;
use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Application\Queries\Crm\CrmListQuestionsQuery;

final readonly class CrmListQuestionsHandler
{
    public function __construct(
        private QuestionRepositoryInterface $repository,
    ) {}

    public function handle(CrmListQuestionsQuery $query): Collection
    {
        return $this->repository->all($query->criteria);
    }
}
