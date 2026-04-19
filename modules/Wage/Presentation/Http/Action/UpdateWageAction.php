<?php
// modules/Wage/Presentation/Http/Action/UpdateWageAction.php
declare(strict_types=1);

namespace Modules\Wage\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Wage\Application\Command\UpdateWage\UpdateWageCommand;
use Modules\Wage\Application\Command\UpdateWage\UpdateWageHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Wage\Domain\ValueObject\WageId;
use Modules\Wage\Presentation\Http\Request\UpdateWageRequest;
use Modules\Wage\Presentation\Http\Presenter\WagePresenter;

final readonly class UpdateWageAction
{
    public function __construct(
        private UpdateWageHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateWageRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateWageCommand(
            id: WageId::fromString($id),
            amount: (float) $request->validated('amount'),
            period: $request->validated('period', 'hourly'),
            currencyId: $request->validated('currency_id'),
        ));

        return $this->responder->success(
            messageKey: 'messages.wage.updated'
        );
    }
}
