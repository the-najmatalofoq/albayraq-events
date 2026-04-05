<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Query\JoinRequest\JoinRequestDTO;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Presentation\Http\Request\ListJoinRequestsRequest;

final class ListJoinRequestsAction
{
    public function __construct(
        private readonly UserJoinRequestRepositoryInterface $repository,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(ListJoinRequestsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $page = (int) ($validated['page'] ?? 1);
        $perPage = (int) ($validated['per_page'] ?? 15);

        $paginator = $this->repository->paginate($page, $perPage);

        // fix: we must make a method for the pagination in the responder, 
        return $this->responder->success([
            'data' => $paginator->getCollection()->map(
                fn($model) => JoinRequestDTO::fromModel($model)
            )->values(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }
}
