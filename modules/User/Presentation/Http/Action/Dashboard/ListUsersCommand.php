<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\UserPresenter;

final readonly class ListUsersCommand
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
        ];

        $users = $this->userRepository->all($filters);

        return $this->responder->success(
            $users->map(fn($user) => UserPresenter::fromDomain($user))->toArray()
        );
    }
}
