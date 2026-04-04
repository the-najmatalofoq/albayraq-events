<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Query\JoinRequest\JoinRequestDTO;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;

final class ListJoinRequestsAction
{
    public function __construct(
        private readonly UserJoinRequestRepositoryInterface $repository,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $page = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 15);

        $paginator = $this->repository->paginate($page, $perPage);

        // fix: we must use responder for all the proejct 
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
