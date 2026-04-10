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
        $contactPhone = $this->contactPhoneRepository->findById($command->contactPhoneId);

        if ($contactPhone === null || $contactPhone->userId->value !== $command->userId->value) {
            throw ContactPhoneNotFoundException::withId($command->contactPhoneId);
        }

        $this->contactPhoneRepository->delete($command->contactPhoneId);
    }
}
