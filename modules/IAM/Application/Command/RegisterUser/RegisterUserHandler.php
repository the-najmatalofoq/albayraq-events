<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

use Illuminate\Support\Facades\DB;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Application\EventDispatcher;
use Modules\Shared\Application\EventDispatchingHandler;

use Modules\IAM\Application\Command\RegisterUser\RegisterAuth\RegisterAuthHandler;
use Modules\IAM\Application\Command\RegisterUser\RegisterProfile\RegisterProfileHandler;
use Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails\RegisterBankDetailsHandler;
use Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone\RegisterContactPhoneHandler;
use Modules\IAM\Application\Command\RegisterUser\RegisterAttachment\RegisterAttachmentHandler;

final readonly class RegisterUserHandler extends EventDispatchingHandler
{
    public function __construct(
        private RegisterAuthHandler $authHandler,
        private RegisterProfileHandler $profileHandler,
        private RegisterBankDetailsHandler $bankHandler,
        private RegisterContactPhoneHandler $contactHandler,
        private RegisterAttachmentHandler $attachmentHandler,
        private UserRepositoryInterface $userRepository,
        EventDispatcher $dispatcher,
    ) {
        parent::__construct($dispatcher);
    }

    public function handle(RegisterUserCommand $command): UserId
    {
        return DB::transaction(function () use ($command) {
            $userId = $this->authHandler->handle($command->auth);

            $this->profileHandler->handle($command->profile, $userId);

            $this->attachmentHandler->handle($command->attachments, $userId);

            $this->bankHandler->handle($command->bank, $userId);

            $this->contactHandler->handle($command->contact, $userId);

            $user = $this->userRepository->findById($userId);
            if ($user) {
                $this->dispatchEvents($user);
            }

            return $userId;
        });
    }
}
