<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\UserUpdateRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserUpdateRequestModel;
use Modules\User\Presentation\Http\Presenter\UserUpdateRequestPresenter;

/**
 * [User] - Show a specific update request that belongs to the authenticated user.
 *
 * GET /api/v1/me/update-requests/{id}
 */
final class ShowMyUpdateRequestAction
{
    public function __construct(
        private readonly JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $model = UserUpdateRequestModel::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$model) {
            abort(404);
        }

        return $this->responder->success(
            data: UserUpdateRequestPresenter::withComparison($model),
            messageKey: 'messages.user_update_requests.found'
        );
    }
}
