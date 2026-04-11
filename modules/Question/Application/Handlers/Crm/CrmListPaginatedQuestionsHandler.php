<?php
// filePath: modules/Question/Application/Handlers/Crm/CrmListPaginatedQuestionsHandler.php
declare(strict_types=1);

namespace Modules\Question\Application\Handlers\Crm;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Application\Queries\Crm\CrmListPaginatedQuestionsQuery;

final readonly class CrmListPaginatedQuestionsHandler
{
    public function __construct(
        private QuestionRepositoryInterface $repository,
    ) {}

    public function handle(CrmListPaginatedQuestionsQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            $query->criteria,
            $query->pagination->perPage
        );
    }
}
