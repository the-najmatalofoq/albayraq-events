<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\UserUpdateRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserUpdateRequestModel;
use Modules\User\Presentation\Http\Presenter\UserUpdateRequestPresenter;

/**
 * [User] - List the authenticated user's own update requests.
 *
 * GET /api/v1/me/update-requests
 * ?status=pending&per_page=15&page=1
 */
final class ListMyUpdateRequestsAction
{
    public function __construct(
        private readonly JsonResponder $responder,
    ) {}

    public function __invoke(\Illuminate\Http\Request $request): JsonResponse
    {
        $userId = auth()->id();
        $perPage = (int) $request->query('per_page', 15);

        $query = UserUpdateRequestModel::where('user_id', $userId)->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $paginator = $query->paginate($perPage);

        return $this->responder->paginated(
            items: collect($paginator->items())
                ->map(fn($m) => UserUpdateRequestPresenter::fromModel($m))
                ->toArray(),
            total: $paginator->total(),
            pagination: \Modules\Shared\Domain\ValueObject\PaginationCriteria::fromArray([
                'page'     => (int) $request->query('page', 1),
                'per_page' => $perPage,
            ]),
        );
    }
}
