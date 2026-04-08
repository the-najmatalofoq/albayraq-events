<?php
// modules/ViolationType/Presentation/Http/Action/GetViolationTypeAction.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action;

use Modules\ViolationType\Application\Query\GetViolationType\GetViolationTypeQuery;
use Modules\ViolationType\Application\Query\GetViolationType\GetViolationTypeHandler;
use Modules\ViolationType\Presentation\Http\Presenter\ViolationTypePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class GetViolationTypeAction
{
    public function __construct(
        private GetViolationTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $violationType = $this->handler->handle(new GetViolationTypeQuery($id));

        if (!$violationType) {
            return $this->responder->notFound();
        }

        return $this->responder->success(
            data: ViolationTypePresenter::fromDomain($violationType)
        );
    }
}
