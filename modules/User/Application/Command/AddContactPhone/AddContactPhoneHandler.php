<?php
// modules/User/Application/Command/AddContactPhone/AddContactPhoneHandler.php
declare(strict_types=1);

namespace Modules\User\Application\Command\AddContactPhone;

use Modules\User\Domain\ContactPhone;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final readonly class AddContactPhoneHandler
{
    public function __construct(
        private ContactPhoneRepositoryInterface $contactPhoneRepository
    ) {}

    public function handle(AddContactPhoneCommand $command): void
    {
        $contactPhone = ContactPhone::create(
            uuid: $this->contactPhoneRepository->nextIdentity(),
            userId: UserId::fromString($command->userId),
            name: $command->name,
            phone: $command->phone,
            relation: $command->relation
        );

        $this->contactPhoneRepository->save($contactPhone);
    }
}
