<?php
// modules/Wage/Presentation/Http/Action/UpdateWageAction.php
declare(strict_types=1);

namespace Modules\Wage\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Wage\Application\Command\UpdateWage\UpdateWageCommand;
use Modules\Wage\Application\Command\UpdateWage\UpdateWageHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateWageAction
{
    public function __construct(
        private UpdateWageHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    // fix: make the (UpdateWage) formRequest for validation
    // fix: make the (currenies) module 
    public function __invoke(Request $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateWageCommand(
            id: $id,
            amount: (float) $request->input('amount'),
            currency: $request->input('currency', 'SAR'),
            period: $request->input('period', 'hourly'),
        ));

        return $this->responder->success(
            messageKey: 'messages.wage.updated'
        );
    }
}
