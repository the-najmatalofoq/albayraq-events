<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteJoinRequest;

use Modules\Shared\Domain\Exception\NotFoundException;
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
            // fix:
            throw new NotFoundException('Join request not found.');
        }

        $this->joinRequestRepository->delete($id);
    }
}
