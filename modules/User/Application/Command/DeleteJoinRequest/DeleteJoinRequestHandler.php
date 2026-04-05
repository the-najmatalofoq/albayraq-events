<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteJoinRequest;

use Modules\User\Domain\Exception\JoinRequestNotFoundException;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\ValueObject\UserJoinRequestId;

final readonly class DeleteJoinRequestHandler
{
    public function __construct(
        private UserJoinRequestRepositoryInterface $joinRequestRepository,
    ) {
    }

    public function handle(DeleteJoinRequestCommand $command): void
    {
        $id = new UserJoinRequestId($command->joinRequestId);

        if ($this->joinRequestRepository->findById($id) === null) {
            throw JoinRequestNotFoundException::forId($command->joinRequestId);
        }

        $this->joinRequestRepository->delete($id);
    }
}
