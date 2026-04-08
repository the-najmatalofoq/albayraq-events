<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action\Dashboard;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Presentation\Http\Presenter\ViolationTypePresenter;
use Modules\ViolationType\Presentation\Http\Request\Dashboard\ViolationTypeFilterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListViolationTypesAction
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository,
        private JsonResponder $responder
    ) {
    }

    public function __invoke(ViolationTypeFilterRequest $request): mixed
    {
        $criteria = $request->toFilterCriteria();
        $types = $this->repository->all($criteria);

        return $this->responder->success(
            data: $types->map(fn($type) => ViolationTypePresenter::fromDomain($type))->toArray()
        );
    }
}
