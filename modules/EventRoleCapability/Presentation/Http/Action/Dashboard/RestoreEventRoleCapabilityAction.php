<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmRestoreEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Dashboard\DashboardRestoreEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Commands\Dashboard\DashboardRestoreEventRoleCapabilityCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class RestoreEventRoleCapabilityAction
{
    public function __construct(
        private RestoreEventRoleCapabilityHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmRestoreEventRoleCapabilityCommand($id));

        return $this->responder->success(messageKey: 'messages.capability.restored');
    }
}
