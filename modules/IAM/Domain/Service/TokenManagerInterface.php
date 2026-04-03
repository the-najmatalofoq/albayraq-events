<?php
// modules/IAM/Domain/Service/TokenManagerInterface.php
declare(strict_types=1);

namespace Modules\IAM\Domain\Service;

use Modules\User\Domain\ValueObject\UserId;

interface TokenManagerInterface
{
    public function issueFromUserId(UserId $userId): array;
    public function refresh(): array;
    public function invalidate(): void;
    public function getUserIdFromToken(): ?UserId;
}
