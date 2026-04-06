<?php
// modules/User/Application/Command/AddContactPhone/AddContactPhoneCommand.php
declare(strict_types=1);

namespace Modules\User\Application\Command\AddContactPhone;

use Modules\User\Domain\ValueObject\Phone;

final readonly class AddContactPhoneCommand
{
    public function __construct(
        public string $userId,
        public string $name,
        public Phone $phone,
        public string $relation = 'emergency',
    ) {
    }
}
