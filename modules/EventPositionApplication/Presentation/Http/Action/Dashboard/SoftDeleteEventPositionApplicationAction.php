<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmSoftDeleteEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Dashboard\DashboardSoftDeleteEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Commands\Dashboard\DashboardSoftDeleteEventPositionApplicationCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class SoftDeleteEventPositionApplicationAction
{
    public function __construct(
        private SoftDeleteEventPositionApplicationHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmSoftDeleteEventPositionApplicationCommand($id));
        return $this->responder->noContent();
    }
}
