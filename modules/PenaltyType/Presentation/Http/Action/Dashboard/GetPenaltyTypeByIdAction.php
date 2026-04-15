<?php
// modules/PenaltyType/Presentation/Http/Action/Dashboard/GetPenaltyTypeByIdAction.php
declare(strict_types=1);

namespace Modules\PenaltyType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\PenaltyType\Application\Query\GetPenaltyType\GetPenaltyTypeQuery;
use Modules\PenaltyType\Application\Query\GetPenaltyType\GetPenaltyTypeHandler;
use Modules\PenaltyType\Presentation\Http\Presenter\PenaltyTypePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetPenaltyTypeByIdAction
{
    public function __construct(
        private GetPenaltyTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $penaltyType = $this->handler->handle(new GetPenaltyTypeQuery($id));

        if (!$penaltyType) {
            return $this->responder->notFound(
                messageKey: 'messages.not_found'
            );
        }

        return $this->responder->success(
            data: PenaltyTypePresenter::fromDomain($penaltyType)
        );
    }
}
