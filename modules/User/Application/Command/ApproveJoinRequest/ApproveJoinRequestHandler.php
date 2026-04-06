<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\ApproveJoinRequest;

use Modules\User\Application\Event\UserJoinRequestApprovedEvent;
use Modules\User\Domain\Exception\JoinRequestNotFoundException;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserJoinRequestId;

final readonly class ApproveJoinRequestHandler
{
    public function __construct(
        private UserJoinRequestRepositoryInterface $joinRequestRepository,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(ApproveJoinRequestCommand $command): void
    {
        $joinRequest = $this->joinRequestRepository->findById(
            new UserJoinRequestId($command->joinRequestId)
        );

        if ($joinRequest === null) {
            throw JoinRequestNotFoundException::forId($command->joinRequestId);
        }

        $user = $this->userRepository->findById($joinRequest->userId);
        if ($user) {
            $user->activate();
            $this->userRepository->save($user);
        }

        $joinRequest->approve($command->reviewedBy, $command->notes);
        $this->joinRequestRepository->save($joinRequest);

        event(new UserJoinRequestApprovedEvent($joinRequest->userId));
    }
}
