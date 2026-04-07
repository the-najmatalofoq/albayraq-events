<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\ForgotPassword;

use Modules\IAM\Domain\Enum\OtpPurposeEnum;
use Modules\IAM\Domain\Exception\InvalidOtpException;
use Modules\IAM\Domain\OtpCode;
use Modules\IAM\Domain\Repository\OtpCodeRepositoryInterface;
use Modules\IAM\Domain\Service\OtpGeneratorInterface;
use Modules\Shared\Domain\Service\DomainEventDispatcher;
use Modules\Shared\Domain\Service\LoggerInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;

final readonly class ForgotPasswordHandler
{
    private const int RESEND_COOLDOWN_SECONDS = 60;

    public function __construct(
        private UserRepositoryInterface    $userRepository,
        private OtpCodeRepositoryInterface $otpRepository,
        private OtpGeneratorInterface      $otpGenerator,
        private DomainEventDispatcher      $eventDispatcher,
        private LoggerInterface            $logger,
    ) {}

    public function handle(ForgotPasswordCommand $command): void
    {
        $user = $this->userRepository->findByEmail($command->email);
        if ($user === null) {
            return;
        }

        $purpose = OtpPurposeEnum::PASSWORD_RESET;

        $existing = $this->otpRepository->findLatestActiveByUserAndPurpose($user->uuid, $purpose);
        if ($existing && $existing->createdAt !== null) {
            $secondsSinceCreated = (new \DateTimeImmutable())->getTimestamp() - $existing->createdAt->getTimestamp();
            if ($secondsSinceCreated < self::RESEND_COOLDOWN_SECONDS) {
                throw InvalidOtpException::tooManyAttempts();
            }
        }

        $this->otpRepository->invalidateAllForUserAndPurpose($user->uuid, $purpose);

        $otpCode = OtpCode::create(
            uuid: $this->otpRepository->nextIdentity(),
            userId: $user->uuid,
            code: $this->otpGenerator->generate(),
            purpose: $purpose,
            expiresInMinutes: 15,
        );

        $this->otpRepository->save($otpCode);
        $this->eventDispatcher->dispatchFrom($otpCode);

        $this->logger->info('Password reset OTP sent.', [
            'user_id' => $user->uuid->value,
            'otp_id'  => $otpCode->uuid->value,
        ]);
    }
}
