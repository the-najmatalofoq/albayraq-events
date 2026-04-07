<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Presentation\Http\Presenter\UserPresenter;
use Modules\User\Presentation\Http\Request\UpdateUserRequest;
// fix: it must be named UpdateUserAction, UpdateUserCommand 
final readonly class UpdateUserCommand
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EmployeeProfileRepositoryInterface $profileRepository,
        private PasswordHasher $passwordHasher,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(UpdateUserRequest $request, string $id): JsonResponse
    {
        $userId = new UserId($id);
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return $this->responder->notFound('User not found');
        }

        $user->updateInfo(
            name: (string) $request->input('name', $user->name),
            phone: $request->has('phone') ? new Phone((string) $request->input('phone')) : $user->phone,
        );

        if ($request->has('email') && $request->input('email') !== $user->email) {
            $user->updateEmail((string) $request->input('email'));
        }

        if ($request->has('password')) {
            $user->changePassword($this->passwordHasher->hash((string) $request->input('password')));
        }

        $this->userRepository->save($user);

        // Profile update
        $profile = $this->profileRepository->findByUserId($userId);
        if ($profile) {
            $profile->update(
                fullName: (string) $request->input('full_name', $profile->fullName),
                identityNumber: (string) $request->input('identity_number', $profile->identityNumber),
                nationalityId: $request->has('nationality_id') ? new NationalityId((string) $request->input('nationality_id')) : $profile->nationalityId,
                birthDate: $request->input('birth_date', $profile->birthDate),
                gender: $request->input('gender', $profile->gender),
                height: (float) $request->input('height', $profile->height),
                weight: (float) $request->input('weight', $profile->weight),
            );
            $this->profileRepository->save($profile);
        }

        return $this->responder->success(
            data: UserPresenter::fromDomain($user),
            messageKey: 'user.updated'
        );
    }
}
