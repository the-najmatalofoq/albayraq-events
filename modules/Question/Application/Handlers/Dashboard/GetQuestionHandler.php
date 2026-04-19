<?php
// filePath: modules/Question/Application/Handlers/Crm/CrmGetQuestionHandler.php
declare(strict_types=1);

namespace Modules\Question\Application\Handlers\Dashboard;

use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\Question\Domain\Question;
use Modules\Question\Application\Queries\Dashboard\DashboardGetQuestionQuery;

final readonly class GetQuestionHandler
{
    public function __construct(
        private QuestionRepositoryInterface $repository,
    ) {}

    public function handle(GetQuestionQuery $query): ?Question
    {
        $id = QuestionId::fromString($query->id);
        
        return $query->withIdTrashed 
            ? $this->repository->findByIdWithTrashed($id)
            : $this->repository->findById($id);
    }
}
