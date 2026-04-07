<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\UserPresenter;
// fix: rename to "Acion" not "Command"

final readonly class ListUsersPaginatedCommand
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', '15');
        $filters = [
            'search' => $request->query('search'),
        ];

        $paginator = $this->userRepository->paginate($perPage, $filters);
        // fix: make a unified of paginated

        return $this->responder->success([
            'data' => $paginator->getCollection()->map(fn($user) => UserPresenter::fromDomain($user))->toArray(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
}
