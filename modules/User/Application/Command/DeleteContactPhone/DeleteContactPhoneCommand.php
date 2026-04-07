<?php
// modules/User/Application/Command/DeleteContactPhone/DeleteContactPhoneCommand.php
declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteContactPhone;

final readonly class DeleteContactPhoneCommand
{
    public function __construct(
        public string $userId,
        public string $contactPhoneId,
    ) {
    }
}
