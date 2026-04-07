<?php
// modules/User/Application/Command/DeleteContactPhone/DeleteContactPhoneHandler.php
declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteContactPhone;

use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\ValueObject\ContactPhoneId;

use Modules\User\Domain\Exception\ContactPhoneNotFoundException;

final readonly class DeleteContactPhoneHandler
{
    public function __construct(
        private ContactPhoneRepositoryInterface $contactPhoneRepository
    ) {}

    public function handle(DeleteContactPhoneCommand $command): void
    {
        $contactPhoneId = ContactPhoneId::fromString($command->contactPhoneId);
        $contactPhone = $this->contactPhoneRepository->findById($contactPhoneId);
        
        if ($contactPhone === null || $contactPhone->userId->value !== $command->userId) {
            throw ContactPhoneNotFoundException::withId($contactPhoneId);
        }

        $this->contactPhoneRepository->delete($contactPhoneId);
    }
}

