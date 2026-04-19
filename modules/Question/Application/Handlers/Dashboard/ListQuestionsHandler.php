<?php
// filePath: modules/Question/Application/Handlers/Crm/CrmListQuestionsHandler.php
declare(strict_types=1);

namespace Modules\Question\Application\Handlers\Dashboard;

use Illuminate\Support\Collection;
use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Application\Queries\Dashboard\DashboardListQuestionsQuery;

final readonly class ListQuestionsHandler
{
    public function __construct(
        private QuestionRepositoryInterface $repository,
    ) {}

    public function handle(ListQuestionsQuery $query): Collection
    {
        return $this->repository->all($query->criteria);
    }
}
