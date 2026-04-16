<?php
// filePath: modules/Question/Application/Handlers/Crm/CrmRestoreQuestionHandler.php
declare(strict_types=1);

namespace Modules\Question\Application\Handlers\Dashboard;

use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\Question\Application\Commands\Dashboard\DashboardRestoreQuestionCommand;

final readonly class RestoreQuestionHandler
{
    public function __construct(
        private QuestionRepositoryInterface $repository,
    ) {}

    public function handle(RestoreQuestionCommand $command): void
    {
        $this->repository->restore(QuestionId::fromString($command->id));
    }
}
