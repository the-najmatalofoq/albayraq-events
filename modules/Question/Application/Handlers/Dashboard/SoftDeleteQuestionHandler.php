<?php
// filePath: modules/Question/Application/Handlers/Crm/CrmSoftDeleteQuestionHandler.php
declare(strict_types=1);

namespace Modules\Question\Application\Handlers\Dashboard;

use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\Question\Application\Commands\Dashboard\DashboardSoftDeleteQuestionCommand;

final readonly class SoftDeleteQuestionHandler
{
    public function __construct(
        private QuestionRepositoryInterface $repository,
    ) {}

    public function handle(SoftDeleteQuestionCommand $command): void
    {
        $this->repository->delete(QuestionId::fromString($command->id));
    }
}
