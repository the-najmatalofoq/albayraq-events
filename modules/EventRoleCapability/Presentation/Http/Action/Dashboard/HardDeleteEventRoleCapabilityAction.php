<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmHardDeleteEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Dashboard\DashboardHardDeleteEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Commands\Dashboard\DashboardHardDeleteEventRoleCapabilityCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class HardDeleteEventRoleCapabilityAction
{
    public function __construct(
        private HardDeleteEventRoleCapabilityHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmHardDeleteEventRoleCapabilityCommand($id));

        return $this->responder->noContent();
    }
}
