<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\SubmitUpdateRequest;

use Modules\User\Domain\UserUpdateRequest;
use Modules\User\Domain\Repository\UserUpdateRequestRepositoryInterface;
use Modules\User\Domain\ValueObject\UserUpdateRequestId;

final readonly class SubmitUpdateRequestHandler
{
    public function __construct(
        private UserUpdateRequestRepositoryInterface $repository
    ) {}

    public function handle(SubmitUpdateRequestCommand $command): UserUpdateRequest
    {
        $userUpdateRequest = new UserUpdateRequest(
            uuid: $this->repository->nextIdentity(),
            userId: $command->userId,
            targetType: $command->targetType,
            targetId: $command->targetId,
            newData: $command->newData,
        );
        
        $this->repository->save($userUpdateRequest);
        
        return $userUpdateRequest;
    }
}
