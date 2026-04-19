<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\UserUpdateRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserUpdateRequestModel;
use Modules\User\Presentation\Http\Presenter\UserUpdateRequestPresenter;

/**
 * [Admin] - Show a single update request with full old vs. new comparison.
 *
 * GET /api/v1/crm/users/update-requests/{id}
 */
final class ShowUpdateRequestAction
{
    public function __construct(
        private readonly JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $model = UserUpdateRequestModel::with('user')->find($id);

        if (!$model) {
            abort(404);
        }

        return $this->responder->success(
            data: UserUpdateRequestPresenter::withComparison($model),
            messageKey: 'messages.user_update_requests.found'
        );
    }
}
