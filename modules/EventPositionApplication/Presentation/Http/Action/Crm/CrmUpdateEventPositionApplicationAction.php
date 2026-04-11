<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmUpdateEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Crm\CrmUpdateEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Commands\Crm\CrmUpdateEventPositionApplicationCommand;
use Modules\EventPositionApplication\Presentation\Http\Request\Crm\CrmUpdateEventPositionApplicationRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmUpdateEventPositionApplicationAction
{
    public function __construct(
        private CrmUpdateEventPositionApplicationHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CrmUpdateEventPositionApplicationRequest $request, string $id): JsonResponse
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
