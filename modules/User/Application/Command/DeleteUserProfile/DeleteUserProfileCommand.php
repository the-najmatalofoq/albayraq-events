<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteUserProfile;

final readonly class DeleteUserProfileCommand
{
    public function __construct(
        public string $userId,
    ) {}
}
