<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\ApproveJoinRequest;

use Modules\Shared\Domain\Exception\NotFoundException;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\ValueObject\UserJoinRequestId;

final readonly class ApproveJoinRequestHandler
{
    public function __construct(
        private UserJoinRequestRepositoryInterface $joinRequestRepository,
    ) {
    }

    public function handle(ApproveJoinRequestCommand $command): void
    {
        $joinRequest = $this->joinRequestRepository->findById(
            new UserJoinRequestId($command->joinRequestId)
        );

        if ($joinRequest === null) {
            // fix: make JoinRequestNotFoundException file like the UserNotFoundException
            throw new NotFoundException('Join request not found.');
        }

        $joinRequest->approve($command->reviewedBy, $command->notes);

        $this->joinRequestRepository->save($joinRequest);
    }
}
