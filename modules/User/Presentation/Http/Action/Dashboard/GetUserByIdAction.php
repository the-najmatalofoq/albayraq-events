<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Presentation\Http\Presenter\UserPresenter;

final readonly class GetUserByIdAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $user = $this->userRepository->findById(new UserId($id));

        if (!$user) {
            return $this->responder->notFound('User not found');
        }

        return $this->responder->success(
            data: UserPresenter::fromDomain($user)
        );
    }
}
