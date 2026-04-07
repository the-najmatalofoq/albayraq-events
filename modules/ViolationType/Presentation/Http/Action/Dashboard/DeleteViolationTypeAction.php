<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action\Dashboard;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteViolationTypeAction
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): mixed
    {
        $typeId = ViolationTypeId::fromString($id);
        $type = $this->repository->findById($typeId);

        if (!$type) {
            return $this->responder->notFound('messages.violation_type_not_found');
        }

        $this->repository->delete($typeId);

        return $this->responder->success(
            data: null,
            status: 200,
            messageKey: 'messages.violation_type_deleted'
        );
    }
}
