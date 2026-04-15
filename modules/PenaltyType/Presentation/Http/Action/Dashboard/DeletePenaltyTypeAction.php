<?php
// modules/PenaltyType/Presentation/Http/Action/Dashboard/DeletePenaltyTypeAction.php
declare(strict_types=1);

namespace Modules\PenaltyType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\PenaltyType\Application\Command\DeletePenaltyType\DeletePenaltyTypeCommand;
use Modules\PenaltyType\Application\Command\DeletePenaltyType\DeletePenaltyTypeHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeletePenaltyTypeAction
{
    public function __construct(
        private DeletePenaltyTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new DeletePenaltyTypeCommand($id));

        return $this->responder->success(
            messageKey: 'messages.deleted'
        );
    }
}
