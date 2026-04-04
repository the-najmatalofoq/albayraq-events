<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;

final class GetAllJoinRequestsAction
{
    public function __construct(
        private readonly UserJoinRequestRepositoryInterface $repository,
        private readonly JsonResponder                      $responder,
    ) {}

    public function __invoke(): JsonResponse
    {
        $all = $this->repository->findAll();

        return $this->responder->success(
            array_map(
                fn($jr) => [
                    'id'          => $jr->uuid->value,
                    'user_id'     => $jr->userId->value,
                    'status'      => $jr->status->value,
                    'reviewed_by' => $jr->reviewedBy,
                    'reviewed_at' => $jr->reviewedAt?->format('c'),
                    'notes'       => $jr->notes,
                    'created_at'  => $jr->createdAt->format('c'),
                    'updated_at'  => $jr->updatedAt?->format('c'),
                ],
                $all
            )
        );
    }
}
