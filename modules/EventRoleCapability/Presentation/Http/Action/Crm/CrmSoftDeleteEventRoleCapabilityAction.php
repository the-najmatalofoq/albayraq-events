<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmSoftDeleteEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Crm\CrmSoftDeleteEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Commands\Crm\CrmSoftDeleteEventRoleCapabilityCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmSoftDeleteEventRoleCapabilityAction
{
    public function __construct(
        private CrmSoftDeleteEventRoleCapabilityHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmSoftDeleteEventRoleCapabilityCommand($id));

        return $this->responder->noContent();
    }
}
