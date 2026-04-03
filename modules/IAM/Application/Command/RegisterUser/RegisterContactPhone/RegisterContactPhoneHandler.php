<?php
// modules/IAM/Application/Command/RegisterUser/RegisterContactPhone/RegisterContactPhoneHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone;

use Modules\User\Domain\ContactPhone;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final readonly class RegisterContactPhoneHandler
{
    public function __construct(
        private ContactPhoneRepositoryInterface $contactPhoneRepository
    ) {}

    public function handle(RegisterContactPhoneCommand $command): void
    {
        $contactPhone = ContactPhone::create(
            uuid: $this->contactPhoneRepository->nextIdentity(),
            userId: UserId::fromString($command->userId),
            name: $command->label, 
            phone: $command->phone,
            relation: (string) $command->label
        );

        $this->contactPhoneRepository->save($contactPhone);
    }
}
