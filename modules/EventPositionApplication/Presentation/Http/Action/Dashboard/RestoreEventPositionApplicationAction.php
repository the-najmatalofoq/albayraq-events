<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmRestoreEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Dashboard\DashboardRestoreEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Commands\Dashboard\DashboardRestoreEventPositionApplicationCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class RestoreEventPositionApplicationAction
{
    public function __construct(
        private RestoreEventPositionApplicationHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmRestoreEventPositionApplicationCommand($id));
        return $this->responder->success(messageKey: 'messages.application.restored');
    }
}
