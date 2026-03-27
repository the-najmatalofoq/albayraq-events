<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RevokeToken;

final readonly class RevokeTokenCommand
{
    public function __construct(
        public string $userEmail,
    ) {}
}
