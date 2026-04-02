<?php
// modules/IAM/Application/Command/RegisterUser/RegisterContactPhone/RegisterContactPhoneHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone;

use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\ContactPhone;
use Modules\User\Domain\ValueObject\UserId;

final readonly class RegisterContactPhoneHandler
{
    public function __construct(
        private ContactPhoneRepositoryInterface $contactRepository,
    ) {}

    public function handle(RegisterContactPhoneCommand $command, UserId $userId): void
    {
        
        $contactId = $this->contactRepository->nextIdentity();
        $contactPhone = ContactPhone::create(
            uuid: $contactId,
            userId: $userId,
            name: $command->name,
            phone: $command->phone,
            relation: $command->relation
        );

        $this->contactRepository->save($contactPhone);
    }
}
