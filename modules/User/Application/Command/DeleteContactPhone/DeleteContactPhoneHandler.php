<?php
// modules/User/Application/Command/DeleteContactPhone/DeleteContactPhoneHandler.php
declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteContactPhone;

use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\ValueObject\ContactPhoneId;

final readonly class DeleteContactPhoneHandler
{
    public function __construct(
        private ContactPhoneRepositoryInterface $contactPhoneRepository
    ) {}

    public function handle(DeleteContactPhoneCommand $command): void
    {
        $contactPhone = $this->contactPhoneRepository->findById(ContactPhoneId::fromString($command->phoneId));
        
        if ($contactPhone && $contactPhone->userId->value === $command->userId) {
            $this->contactPhoneRepository->delete(ContactPhoneId::fromString($command->phoneId));
        }
    }
}

