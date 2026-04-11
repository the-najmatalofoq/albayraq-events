<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmRestoreEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Crm\CrmRestoreEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Commands\Crm\CrmRestoreEventRoleCapabilityCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmRestoreEventRoleCapabilityAction
{
    public function __construct(
        private CrmRestoreEventRoleCapabilityHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmRestoreEventRoleCapabilityCommand($id));

        return $this->responder->success(messageKey: 'messages.capability.restored');
    }
}
