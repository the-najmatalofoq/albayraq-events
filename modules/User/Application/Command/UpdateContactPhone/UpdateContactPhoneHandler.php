<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateContactPhone;

use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\ValueObject\ContactPhoneId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\Exception\ContactPhoneNotFoundException;

final readonly class UpdateContactPhoneHandler
{
    public function __construct(
        private ContactPhoneRepositoryInterface $contactPhoneRepository,
    ) {
    }

    public function handle(UpdateContactPhoneCommand $command): void
    {
        $contactPhoneId = ContactPhoneId::fromString($command->contactPhoneId);
        $contactPhone = $this->contactPhoneRepository->findById($contactPhoneId);

        if ($contactPhone === null || $contactPhone->userId->value !== $command->userId) {
            throw ContactPhoneNotFoundException::withId($contactPhoneId);
        }

        $contactPhone->update(
            name: $command->name,
            phone: $command->phone,
            relation: $command->relation
        );

        $this->contactPhoneRepository->save($contactPhone);
    }
}
