<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteAccount;

use Modules\User\Domain\ValueObject\UserId;

final readonly class DeleteAccountCommand
{
    public function __construct(
        public UserId $userId,
    ) {}
}
