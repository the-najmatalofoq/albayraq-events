<?php
// modules/User/Presentation/Http/Action/GetUserProfileAction.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Modules\User\Domain\Repository\UserProfileRepositoryInterface;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\User\Presentation\Http\Presenter\UserProfilePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetUserProfileAction
{
    public function __construct(
        private UserProfileRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(): mixed
    {
        $userId = UserId::fromString(auth()->id());
        $profile = $this->repository->findByUserId($userId);

        if (!$profile) {
            return $this->responder->error('PROFILE_NOT_FOUND', 404, 'User profile not found');
        }

        return $this->responder->success(
            data: UserProfilePresenter::fromDomain($profile)
        );
    }
}
