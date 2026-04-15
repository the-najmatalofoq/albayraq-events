<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Presentation\Http\Presenter\UserPresenter;
use Modules\User\Presentation\Http\Request\Dashboard\UpdateUserRequest;

// fix: I think this class only update (email, phone, email, name, password)
final readonly class UpdateUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasher $passwordHasher,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateUserRequest $request, string $id): JsonResponse
    {
        // $userId = new UserId($id);
        // $user = $this->userRepository->findById($userId);

        // if (!$user) {
        //     return $this->responder->notFound('User not found');
        // }

        // if ($request->has('email')) {
        //     $user->updateEmail((string) $request->input('email'));
        // }

        // if ($request->has('name') || $request->has('phone')) {
        //     $user->updateInfo(
        //         name: (string) $request->input('name', $user->name),
        //         phone: $request->has('phone') ? new Phone((string) $request->input('phone')) : $user->phone
        //     );
        // }

        // if ($request->has('password')) {
        //     $user->changePassword($this->passwordHasher->hash((string) $request->input('password')));
        // }

        // $this->userRepository->save($user);

        return $this->responder->success(
            // data: UserPresenter::fromDomain($user),
            // messageKey: 'user.updated'
        );
    }
}
