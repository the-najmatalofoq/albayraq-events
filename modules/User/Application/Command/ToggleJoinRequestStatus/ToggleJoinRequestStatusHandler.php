<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\ToggleJoinRequestStatus;

use Modules\User\Domain\Exception\JoinRequestNotFoundException;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\ValueObject\UserJoinRequestId;

final readonly class ToggleJoinRequestStatusHandler
{
    public function __construct(
        private UserJoinRequestRepositoryInterface $joinRequestRepository,
    ) {}

    public function handle(ToggleJoinRequestStatusCommand $command): void
    {
        $joinRequest = $this->joinRequestRepository->findById(
            new UserJoinRequestId($command->joinRequestId)
        );

        if ($joinRequest === null) {
            throw JoinRequestNotFoundException::forId($command->joinRequestId);
        }

        $joinRequest->toggleStatus();

        $this->joinRequestRepository->save($joinRequest);
    }
}
