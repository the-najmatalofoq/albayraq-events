<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmSoftDeleteEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Dashboard\DashboardSoftDeleteEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Commands\Dashboard\DashboardSoftDeleteEventRoleCapabilityCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class SoftDeleteEventRoleCapabilityAction
{
    public function __construct(
        private SoftDeleteEventRoleCapabilityHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmSoftDeleteEventRoleCapabilityCommand($id));

        return $this->responder->noContent();
    }
}
