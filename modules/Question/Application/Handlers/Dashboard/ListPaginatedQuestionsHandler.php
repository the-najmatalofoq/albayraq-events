<?php
// filePath: modules/Question/Application/Handlers/Crm/CrmListPaginatedQuestionsHandler.php
declare(strict_types=1);

namespace Modules\Question\Application\Handlers\Dashboard;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Application\Queries\Dashboard\DashboardListPaginatedQuestionsQuery;

final readonly class ListPaginatedQuestionsHandler
{
    public function __construct(
        private QuestionRepositoryInterface $repository,
    ) {}

    public function handle(ListPaginatedQuestionsQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            $query->criteria,
            $query->pagination->perPage
        );
    }
}
