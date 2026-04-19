<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmHardDeleteEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Dashboard\DashboardHardDeleteEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Commands\Dashboard\DashboardHardDeleteEventPositionApplicationCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class HardDeleteEventPositionApplicationAction
{
    public function __construct(
        private HardDeleteEventPositionApplicationHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmHardDeleteEventPositionApplicationCommand($id));
        return $this->responder->noContent();
    }
}
