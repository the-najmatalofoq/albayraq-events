<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmSoftDeleteEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Crm\CrmSoftDeleteEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Commands\Crm\CrmSoftDeleteEventPositionApplicationCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmSoftDeleteEventPositionApplicationAction
{
    public function __construct(
        private CrmSoftDeleteEventPositionApplicationHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmSoftDeleteEventPositionApplicationCommand($id));
        return $this->responder->noContent();
    }
}
