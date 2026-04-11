<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmRestoreEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Crm\CrmRestoreEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Commands\Crm\CrmRestoreEventPositionApplicationCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmRestoreEventPositionApplicationAction
{
    public function __construct(
        private CrmRestoreEventPositionApplicationHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmRestoreEventPositionApplicationCommand($id));
        return $this->responder->success(messageKey: 'messages.application.restored');
    }
}
