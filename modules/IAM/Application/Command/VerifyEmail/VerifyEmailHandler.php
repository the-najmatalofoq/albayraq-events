<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\VerifyEmail;

use Modules\IAM\Domain\Enum\OtpPurposeEnum;
use Modules\IAM\Domain\Exception\InvalidOtpException;
use Modules\IAM\Domain\Repository\OtpCodeRepositoryInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final readonly class VerifyEmailHandler
{
    public function __construct(
        private OtpCodeRepositoryInterface $otpRepository,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(VerifyEmailCommand $command): void
    {
        $userId = UserId::fromString($command->userId);
        $purpose = OtpPurposeEnum::EMAIL_VERIFICATION;

        $otpCode = $this->otpRepository->findLatestActiveByUserAndPurpose($userId, $purpose);

        if ($otpCode === null) {
            throw InvalidOtpException::invalid();
        }

        if ($otpCode->isExpired()) {
            throw InvalidOtpException::expired();
        }

        if ($otpCode->isVerified()) {
            throw InvalidOtpException::alreadyVerified();
        }

        if ($otpCode->code !== $command->code) {
            throw InvalidOtpException::invalid();
        }

        $otpCode->verify();
        $this->otpRepository->save($otpCode);

        $user = $this->userRepository->findById($userId);
        if ($user !== null) {
            $user->markEmailAsVerified();
            $this->userRepository->save($user);
        }
    }
}
