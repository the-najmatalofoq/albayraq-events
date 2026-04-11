<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmHardDeleteEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Crm\CrmHardDeleteEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Commands\Crm\CrmHardDeleteEventPositionApplicationCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmHardDeleteEventPositionApplicationAction
{
    public function __construct(
        private CrmHardDeleteEventPositionApplicationHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmHardDeleteEventPositionApplicationCommand($id));
        return $this->responder->noContent();
    }
}
