<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmHardDeleteEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Crm\CrmHardDeleteEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Commands\Crm\CrmHardDeleteEventRoleCapabilityCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmHardDeleteEventRoleCapabilityAction
{
    public function __construct(
        private CrmHardDeleteEventRoleCapabilityHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmHardDeleteEventRoleCapabilityCommand($id));

        return $this->responder->noContent();
    }
}
