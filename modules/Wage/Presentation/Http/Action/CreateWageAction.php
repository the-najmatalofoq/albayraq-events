<?php
// modules/Wage/Presentation/Http/Action/CreateWageAction.php
declare(strict_types=1);

namespace Modules\Wage\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Wage\Application\Command\CreateWage\CreateWageCommand;
use Modules\Wage\Application\Command\CreateWage\CreateWageHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Wage\Presentation\Http\Request\CreateWageRequest;

final readonly class CreateWageAction
{
    public function __construct(
        private CreateWageHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CreateWageRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CreateWageCommand(
            wageableId: $request->validated('wageable_id'),
            wageableType: $request->validated('wageable_type'),
            amount: (float) $request->validated('amount'),
            period: $request->validated('period'),
            currencyId: $request->validated('currency_id'),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.wage.created'
        );
    }
}
