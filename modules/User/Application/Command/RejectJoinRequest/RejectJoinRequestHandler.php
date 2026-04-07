<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\RejectJoinRequest;

use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\ValueObject\UserJoinRequestId;

final readonly class RejectJoinRequestHandler
{
    public function __construct(
        private UserJoinRequestRepositoryInterface $repository,
    ) {
    }

    public function handle(RejectJoinRequestCommand $command): void
    {
        $id = UserJoinRequestId::fromString($command->requestId);
        $request = $this->repository->findById($id);

        if ($request === null) {
            return;
        }

        $request->reject($command->reviewedBy, $command->notes);

        $this->repository->save($request);
    }
}
