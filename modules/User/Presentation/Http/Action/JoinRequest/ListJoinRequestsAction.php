<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Query\JoinRequest\JoinRequestDTO;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Presentation\Http\Request\ListJoinRequestsRequest;

use Modules\Shared\Domain\ValueObject\PaginationCriteria;

final class ListJoinRequestsAction
{
    public function __construct(
        private readonly UserJoinRequestRepositoryInterface $repository,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(ListJoinRequestsRequest $request): JsonResponse
    {
        $pagination = PaginationCriteria::fromArray($request->validated());

        $paginator = $this->repository->paginate($pagination->page, $pagination->perPage);

        return $this->responder->paginated(
            items: $paginator->getCollection()->map(
                fn($model) => JoinRequestDTO::fromModel($model)
            )->values()->all(),
            total: $paginator->total(),
            pagination: $pagination,
            presenter: fn(JoinRequestDTO $dto) => [
                'id' => $dto->id,
                'user_id' => $dto->userId,
                'status' => $dto->status,
                'reviewed_by' => $dto->reviewedBy,
                'reviewed_at' => $dto->reviewedAt,
                'notes' => $dto->notes,
                'created_at' => $dto->createdAt,
                'updated_at' => $dto->updatedAt,
            ]
        );
    }
}
