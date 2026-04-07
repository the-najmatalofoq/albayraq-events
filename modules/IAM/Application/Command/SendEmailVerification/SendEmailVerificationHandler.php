<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\SendEmailVerification;

use Modules\IAM\Domain\Enum\OtpPurposeEnum;
use Modules\IAM\Domain\Exception\InvalidOtpException;
use Modules\IAM\Domain\OtpCode;
use Modules\IAM\Domain\Repository\OtpCodeRepositoryInterface;
use Modules\IAM\Domain\Service\OtpGeneratorInterface;
use Modules\Shared\Domain\Service\DomainEventDispatcher;
use Modules\Shared\Domain\Service\LoggerInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final readonly class SendEmailVerificationHandler
{
    private const int RESEND_COOLDOWN_SECONDS = 60;

    public function __construct(
        private OtpCodeRepositoryInterface $otpRepository,
        private OtpGeneratorInterface      $otpGenerator,
        private UserRepositoryInterface    $userRepository,
        private DomainEventDispatcher      $eventDispatcher,
        private LoggerInterface            $logger,
    ) {}

    public function handle(SendEmailVerificationCommand $command): void
    {
        $userId = UserId::fromString($command->userId);
        $purpose = OtpPurposeEnum::EMAIL_VERIFICATION;

        $existing = $this->otpRepository->findLatestActiveByUserAndPurpose($userId, $purpose);
        if ($existing && $existing->createdAt !== null) {
            $secondsSinceCreated = (new \DateTimeImmutable())->getTimestamp() - $existing->createdAt->getTimestamp();
            if ($secondsSinceCreated < self::RESEND_COOLDOWN_SECONDS) {
                throw InvalidOtpException::tooManyAttempts();
            }
        }

        $this->otpRepository->invalidateAllForUserAndPurpose($userId, $purpose);

        $otpCode = OtpCode::create(
            uuid: $this->otpRepository->nextIdentity(),
            userId: $userId,
            code: $this->otpGenerator->generate(),
            purpose: $purpose,
            expiresInMinutes: 10,
        );

        $this->otpRepository->save($otpCode);
        $this->eventDispatcher->dispatchFrom($otpCode);

        $this->logger->info('Email verification OTP sent.', [
            'user_id' => $userId->value,
            'otp_id'  => $otpCode->uuid->value,
        ]);
    }
}
