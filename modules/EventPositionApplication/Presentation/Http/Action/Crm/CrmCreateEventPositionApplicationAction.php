<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmCreateEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Crm\CrmCreateEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Commands\Crm\CrmCreateEventPositionApplicationCommand;
use Modules\EventPositionApplication\Presentation\Http\Request\Crm\CrmCreateEventPositionApplicationRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmCreateEventPositionApplicationAction
{
    public function __construct(
        private CrmCreateEventPositionApplicationHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CrmCreateEventPositionApplicationRequest $request): JsonResponse
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
