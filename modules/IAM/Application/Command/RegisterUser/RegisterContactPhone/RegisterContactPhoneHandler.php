<?php
// modules/IAM/Application/Command/RegisterUser/RegisterContactPhone/RegisterContactPhoneHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone;

use Modules\User\Domain\ContactPhone;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;

final readonly class RegisterContactPhoneHandler
{
    public function __construct(
        private ContactPhoneRepositoryInterface $contactPhoneRepository
    ) {}

    public function handle(RegisterContactPhoneCommand $command): void
    {
        $contactPhone = ContactPhone::create(
            uuid: $this->contactPhoneRepository->nextIdentity(),
            userId: $command->userId,
            phone: $command->phone,
            name: $command->contactName,
            relation: $command->relation
        );

        $this->contactPhoneRepository->save($contactPhone);
    }
}
