<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmUpdateEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Dashboard\DashboardUpdateEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Commands\Dashboard\DashboardUpdateEventPositionApplicationCommand;
use Modules\EventPositionApplication\Presentation\Http\Request\Dashboard\DashboardUpdateEventPositionApplicationRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateEventPositionApplicationAction
{
    public function __construct(
        private UpdateEventPositionApplicationHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateEventPositionApplicationRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new CrmUpdateEventPositionApplicationCommand(
            id: $id,
            userId: $request->input('user_id'),
            positionId: $request->input('position_id'),
            status: $request->input('status'),
            rankingScore: (float) $request->input('ranking_score'),
        ));
        return $this->responder->success(messageKey: 'messages.application.updated');
    }
}
