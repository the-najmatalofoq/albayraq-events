<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\CreateJoinRequest;

use DateTimeImmutable;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\UserJoinRequest;
use Modules\User\Domain\ValueObject\UserId;

final readonly class CreateJoinRequestHandler
{
    public function __construct(
        private UserJoinRequestRepositoryInterface $joinRequestRepository,
    ) {
    }

    public function handle(CreateJoinRequestCommand $command): void
    {
        $joinRequest = UserJoinRequest::create(
            uuid: $this->joinRequestRepository->nextIdentity(),
            userId: new UserId($command->userId),
            createdAt: new DateTimeImmutable(),
        );

        $this->joinRequestRepository->save($joinRequest);
    }
}
