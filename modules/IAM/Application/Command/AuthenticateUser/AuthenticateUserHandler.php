<?php
// modules/IAM/Application/Command/AuthenticateUser/AuthenticateUserHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\AuthenticateUser;

use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\IAM\Domain\Exception\InvalidCredentialsException;
use Modules\IAM\Domain\Service\UserAccessValidator;
use Modules\Notification\Application\Command\RegisterDeviceToken\RegisterDeviceTokenCommand;
use Modules\Notification\Application\Command\RegisterDeviceToken\RegisterDeviceTokenHandler;

use Modules\IAM\Domain\Event\UserLoggedIntoNewDevice;

final readonly class AuthenticateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasher $passwordHasher,
        private TokenManager $tokenManager,
        private UserAccessValidator $accessValidator,
        private RegisterDeviceTokenHandler $deviceTokenHandler,
    ) {}

    public function handle(AuthenticateUserCommand $command): array
    {
        $user = $this->userRepository->findByEmail($command->email);
        if (!$user || !$this->passwordHasher->verify($command->password, $user->password)) {
            throw new InvalidCredentialsException(__('messages.errors.credentials_invalid'));
        }

        // $this->accessValidator->validateLogin($user);

        // Notify other devices about login from a new device
        UserLoggedIntoNewDevice::dispatch(
            $user,
            $command->deviceName,
            $user->preferred_locale ?? 'ar'
        );

        if ($command->fcmToken) {
            $this->deviceTokenHandler->handle(new RegisterDeviceTokenCommand(
                userId: $user->uuid,
                token: $command->fcmToken,
                deviceId: $command->deviceId,
                platform: $command->platform,
                deviceName: $command->deviceName,
            ));
        }

        return [
            'tokens' => $this->tokenManager->createToken($user->uuid->value, [
                'device_name' => $command->deviceName
            ]),
            'user' => $user,
        ];
    }
}
