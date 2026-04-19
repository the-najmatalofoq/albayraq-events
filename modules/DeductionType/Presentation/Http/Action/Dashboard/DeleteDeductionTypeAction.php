<?php
// modules/DeductionType/Presentation/Http/Action/Dashboard/DeleteDeductionTypeAction.php
declare(strict_types=1);

namespace Modules\DeductionType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\DeductionType\Application\Command\DeleteDeductionType\DeleteDeductionTypeCommand;
use Modules\DeductionType\Application\Command\DeleteDeductionType\DeleteDeductionTypeHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteDeductionTypeAction
{
    public function __construct(
        private DeleteDeductionTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new DeleteDeductionTypeCommand($id));

        return $this->responder->success(
            messageKey: 'messages.deleted'
        );
    }
}
