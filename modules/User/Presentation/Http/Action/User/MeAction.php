<?php
// modules/User/Presentation/Http/Action/MeAction.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\User;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\UserPresenter;
use Modules\User\Presentation\Http\Presenter\EmployeeProfilePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class MeAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private UserRepositoryInterface $userRepository,
        private EmployeeProfileRepositoryInterface $profileRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if (!$userId) {
            return $this->responder->unauthorized();
        }

        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return $this->responder->unauthorized();
        }

        $profile = $this->profileRepository->findByUserId($userId);

        return $this->responder->success(
            data: [
                'user' => UserPresenter::fromDomain($user),
                'profile' => $profile ? EmployeeProfilePresenter::fromDomain($profile) : null,
            ]
        );
    }
}
