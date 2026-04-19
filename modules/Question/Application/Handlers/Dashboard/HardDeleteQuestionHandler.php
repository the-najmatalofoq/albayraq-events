<?php
// filePath: modules/Question/Application/Handlers/Crm/CrmHardDeleteQuestionHandler.php
declare(strict_types=1);

namespace Modules\Question\Application\Handlers\Dashboard;

use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\Question\Application\Commands\Dashboard\DashboardHardDeleteQuestionCommand;

final readonly class HardDeleteQuestionHandler
{
    public function __construct(
        private QuestionRepositoryInterface $repository,
    ) {}

    public function handle(HardDeleteQuestionCommand $command): void
    {
        $this->repository->hardDelete(QuestionId::fromString($command->id));
    }
}
