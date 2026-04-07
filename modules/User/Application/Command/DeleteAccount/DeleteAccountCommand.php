<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteAccount;

final readonly class DeleteAccountCommand
{
    public function __construct(
        public string $userId,
    ) {}
}
