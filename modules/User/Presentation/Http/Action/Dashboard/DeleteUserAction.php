<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final readonly class DeleteUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $userId = new UserId($id);
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return $this->responder->notFound('User not found');
        }

        $this->userRepository->delete($userId);

        return $this->responder->noContent();
    }
}
