<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RevokeToken;

use Modules\IAM\Domain\Service\TokenManagerInterface;

final readonly class RevokeTokenHandler
{
    public function __construct(
        private TokenManagerInterface $tokenManager,
    ) {
    }

    public function handle(RevokeTokenCommand $command): void
    {
        $this->tokenManager->revokeAllTokens($command->userEmail);
    }
}
