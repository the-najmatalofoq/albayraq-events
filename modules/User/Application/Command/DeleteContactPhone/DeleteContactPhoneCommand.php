<?php
// modules/User/Application/Command/DeleteContactPhone/DeleteContactPhoneCommand.php
declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteContactPhone;

use Modules\User\Domain\ValueObject\ContactPhoneId;
use Modules\User\Domain\ValueObject\UserId;

final readonly class DeleteContactPhoneCommand
{
    public function __construct(
        public UserId $userId,
        public ContactPhoneId $contactPhoneId,
    ) {
    }
}
