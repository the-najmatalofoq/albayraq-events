<?php
// modules/IAM/Application/Command/RegisterUser/RegisterContactPhone/RegisterContactPhoneCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone;

use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;

final readonly class RegisterContactPhoneCommand
{
    public function __construct(
        public UserId $userId,
        public string $contactName,
        public Phone $phone,
        public string $relation,
    ) {}
}
