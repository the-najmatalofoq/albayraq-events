<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\ResetPassword;

use Modules\IAM\Domain\Enum\OtpPurposeEnum;
use Modules\IAM\Domain\Exception\InvalidOtpException;
use Modules\IAM\Domain\Exception\PasswordResetFailedException;
use Modules\IAM\Domain\Repository\OtpCodeRepositoryInterface;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\User\Domain\Repository\UserRepositoryInterface;

final readonly class ResetPasswordHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private OtpCodeRepositoryInterface $otpRepository,
        private PasswordHasher $passwordHasher,
    ) {
    }

    public function handle(ResetPasswordCommand $command): void
    {
        $user = $this->userRepository->findByEmail($command->email);
        if ($user === null) {
            throw PasswordResetFailedException::userNotFound();
        }

        $purpose = OtpPurposeEnum::PASSWORD_RESET;
        $otpCode = $this->otpRepository->findLatestActiveByUserAndPurpose($user->uuid, $purpose);

        if ($otpCode === null || $otpCode->code !== $command->code) {
            throw InvalidOtpException::invalid();
        }

        if ($otpCode->isExpired()) {
            throw InvalidOtpException::expired();
        }

        $otpCode->verify();
        $this->otpRepository->save($otpCode);

        $user->changePassword($this->passwordHasher->hash($command->password));
        $this->userRepository->save($user);
    }
}
