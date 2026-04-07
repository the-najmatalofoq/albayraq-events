<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\EmployeeProfilePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetProfileAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private EmployeeProfileRepositoryInterface $profileRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $profile = $this->profileRepository->findByUserId($userId);

        return $this->responder->success(
            data: EmployeeProfilePresenter::fromDomain($profile)
        );
    }
}
