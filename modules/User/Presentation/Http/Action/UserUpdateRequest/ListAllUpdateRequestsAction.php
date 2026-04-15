<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\UserUpdateRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserUpdateRequestModel;
use Modules\User\Presentation\Http\Presenter\UserUpdateRequestPresenter;
use Modules\User\Presentation\Http\Request\UserUpdateRequest\ListUpdateRequestsFilterRequest;

/**
 * [Admin] - List all user update requests with filtering + pagination.
 *
 * GET /api/v1/crm/users/update-requests
 * ?status=pending&target_type=BankDetailModel&user_id=...&per_page=20&page=1
 */
final class ListAllUpdateRequestsAction
{
    public function __construct(
        private readonly JsonResponder $responder,
    ) {}

    public function __invoke(ListUpdateRequestsFilterRequest $request): JsonResponse
    {
        $query = UserUpdateRequestModel::with('user')->latest();

        if ($status = $request->validated('status')) {
            $query->where('status', $status);
        }

        if ($targetType = $request->validated('target_type')) {
            // Accept short class name (e.g. BankDetailModel) or full namespace
            if (!str_contains($targetType, '\\')) {
                $targetType = 'Modules\\User\\Infrastructure\\Persistence\\Eloquent\\Models\\' . $targetType;
            }
            $query->where('target_type', $targetType);
        }

        if ($userId = $request->validated('user_id')) {
            $query->where('user_id', $userId);
        }

        $perPage  = $request->getPerPage();
        $paginator = $query->paginate($perPage);

        return $this->responder->paginated(
            items: collect($paginator->items())
                ->map(fn($m) => UserUpdateRequestPresenter::fromModel($m))
                ->toArray(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
        );
    }
}
