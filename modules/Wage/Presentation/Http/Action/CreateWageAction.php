<?php
// modules/Wage/Presentation/Http/Action/CreateWageAction.php
declare(strict_types=1);

namespace Modules\Wage\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Wage\Application\Command\CreateWage\CreateWageCommand;
use Modules\Wage\Application\Command\CreateWage\CreateWageHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateWageAction
{
    public function __construct(
        private CreateWageHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $id = $this->handler->handle(new CreateWageCommand(
            wageableId: $request->input('wageable_id'),
            wageableType: $request->input('wageable_type'),
            amount: (float) $request->input('amount'),
            currency: $request->input('currency', 'SAR'),
            period: $request->input('period', 'hourly'),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.wage.created'
        );
    }
}
