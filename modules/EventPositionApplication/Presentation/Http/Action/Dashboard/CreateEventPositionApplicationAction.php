<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmCreateEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Dashboard\DashboardCreateEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Commands\Dashboard\DashboardCreateEventPositionApplicationCommand;
use Modules\EventPositionApplication\Presentation\Http\Request\Dashboard\DashboardCreateEventPositionApplicationRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateEventPositionApplicationAction
{
    public function __construct(
        private CreateEventPositionApplicationHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CreateEventPositionApplicationRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CrmCreateEventPositionApplicationCommand(
            userId: $request->input('user_id'),
            positionId: $request->input('position_id'),
            status: $request->input('status', 'pending'),
            rankingScore: (float) $request->input('ranking_score', 0),
        ));
        return $this->responder->created(data: ['id' => $id->value], messageKey: 'messages.application.created');
    }
}
