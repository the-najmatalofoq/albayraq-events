<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\ValueObject\EmployeeProfileId;
use Modules\User\Presentation\Http\Presenter\EmployeeProfilePresenter;

final readonly class GetProfileByIdAction
{
    public function __construct(
        private EmployeeProfileRepositoryInterface $profileRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $profile = $this->profileRepository->findById(new EmployeeProfileId($id));

        if (!$profile) {
            return $this->responder->notFound('Profile not found');
        }

        return $this->responder->success(
            data: EmployeeProfilePresenter::fromDomain($profile)
        );
    }
}
