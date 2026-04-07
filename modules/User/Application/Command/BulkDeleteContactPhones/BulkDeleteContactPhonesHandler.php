<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\BulkDeleteContactPhones;

use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\ValueObject\ContactPhoneId;
use Modules\User\Domain\ValueObject\UserId;

final readonly class BulkDeleteContactPhonesHandler
{
    public function __construct(
        private ContactPhoneRepositoryInterface $contactPhoneRepository,
    ) {
    }

    public function handle(BulkDeleteContactPhonesCommand $command): void
    {
        $userId = UserId::fromString($command->userId);
        
        $ids = array_map(
            fn(string $id) => ContactPhoneId::fromString($id),
            $command->contactPhoneIds
        );

        $this->contactPhoneRepository->deleteBulk($userId, $ids);
    }
}
