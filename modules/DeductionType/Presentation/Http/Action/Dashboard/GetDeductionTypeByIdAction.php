<?php
// modules/DeductionType/Presentation/Http/Action/Dashboard/GetDeductionTypeByIdAction.php
declare(strict_types=1);

namespace Modules\DeductionType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\DeductionType\Application\Query\GetDeductionType\GetDeductionTypeQuery;
use Modules\DeductionType\Application\Query\GetDeductionType\GetDeductionTypeHandler;
use Modules\DeductionType\Presentation\Http\Presenter\DeductionTypePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetDeductionTypeByIdAction
{
    public function __construct(
        private GetDeductionTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $deductionType = $this->handler->handle(new GetDeductionTypeQuery($id));

        if (!$deductionType) {
            return $this->responder->notFound(
                messageKey: 'messages.not_found'
            );
        }

        return $this->responder->success(
            data: DeductionTypePresenter::fromDomain($deductionType)
        );
    }
}
