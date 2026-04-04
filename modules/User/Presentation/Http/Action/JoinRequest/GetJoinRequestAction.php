<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\Exception\NotFoundException;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\ValueObject\UserJoinRequestId;

final class GetJoinRequestAction
{
    public function __construct(
        private readonly UserJoinRequestRepositoryInterface $repository,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $joinRequest = $this->repository->findById(new UserJoinRequestId($id));

        if ($joinRequest === null) {
            // fix: make JoinRequestNotFoundException file like the UserNotFoundException
            throw new NotFoundException('Join request not found.');
        }

        return $this->responder->success([
            'id' => $joinRequest->uuid->value,
            'user_id' => $joinRequest->userId->value,
            'status' => $joinRequest->status->value,
            'reviewed_by' => $joinRequest->reviewedBy,
            'reviewed_at' => $joinRequest->reviewedAt?->format('c'),
            'notes' => $joinRequest->notes,
            'created_at' => $joinRequest->createdAt->format('c'),
            'updated_at' => $joinRequest->updatedAt?->format('c'),
        ]);
    }
}
