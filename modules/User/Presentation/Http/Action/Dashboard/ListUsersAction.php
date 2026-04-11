<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\UserPresenter;

final readonly class ListUsersAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(BaseFilterRequest $request): JsonResponse
    {
        $filters = $request->toFilterCriteria();

        $users = $this->userRepository->all($filters);

        return $this->responder->success(
            data: $users->map(fn($user) => UserPresenter::fromDomain($user))->toArray()
        );
    }
}
