<?php
// modules/IAM/Domain/Service/UserAccessValidator.php
declare(strict_types=1);

namespace Modules\IAM\Domain\Service;

use Modules\IAM\Domain\Exception\UserNotApprovedException;
use Modules\IAM\Domain\Exception\UserNotVerifiedException;
use Modules\IAM\Domain\Exception\UserPendingException;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\User;

final readonly class UserAccessValidator
{
    public function __construct(
        private UserJoinRequestRepositoryInterface $userJoinRequestRepository,
        private RoleRepository $roleRepository,
    ) {}

    public function validateLogin(User $user): void
    {
        $employeeRole = $this->roleRepository->findBySlug(RoleSlugEnum::EMPLOYEE);

        if ($employeeRole && $user->hasRole($employeeRole->uuid)) {
            $latestJoinRequest = $this->userJoinRequestRepository->findLatestByUserId($user->uuid);
            if ($latestJoinRequest) {
                if ($latestJoinRequest->status->isRejected()) {
                    throw UserNotApprovedException::forUser(__('messages.user.account_not_active'), 'messages.errors.user_not_approved');
                }
                if ($latestJoinRequest->status->isPending()) {
                    throw UserPendingException::create(__('messages.user.account_not_active'), 'messages.errors.user_pending');
                }
            }

            if (!$user->emailVerifiedAt) {
                throw UserNotVerifiedException::forEmail(__('messages.user.account_not_active'), 'messages.errors.user_not_verified');
            }
        }
    }

    public function validateRegister(User $user): void
    {
        $employeeRole = $this->roleRepository->findBySlug(RoleSlugEnum::EMPLOYEE);

        if ($employeeRole && $user->hasRole($employeeRole->uuid)) {
            $latestJoinRequest = $this->userJoinRequestRepository->findLatestByUserId($user->uuid);
            if ($latestJoinRequest) {
                if ($latestJoinRequest->status->isRejected()) {
                    throw UserNotApprovedException::forUser(__('messages.user.user_already_exists'), __('messages.errors.user_not_approved'));
                }
                if ($latestJoinRequest->status->isPending()) {
                    throw UserPendingException::create(__('messages.user.user_already_exists'), __('messages.errors.user_pending'));
                }
            }

            if (!$user->emailVerifiedAt) {
                throw UserNotVerifiedException::forEmail(__('messages.user.user_already_exists'), __('messages.errors.email_not_verified'));
            }
        }
    }
}
