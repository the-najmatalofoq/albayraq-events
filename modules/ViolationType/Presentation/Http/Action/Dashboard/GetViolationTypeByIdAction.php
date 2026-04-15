<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action\Dashboard;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\ViolationType\Presentation\Http\Presenter\ViolationTypePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetViolationTypeByIdAction
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): mixed
    {
        $type = $this->repository->findById(ViolationTypeId::fromString($id));

        if (!$type) {
            return $this->responder->notFound('messages.not_found');
        }

        return $this->responder->success(ViolationTypePresenter::fromDomain($type));
    }
}
