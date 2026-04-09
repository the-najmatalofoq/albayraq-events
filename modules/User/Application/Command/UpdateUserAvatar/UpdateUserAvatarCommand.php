<?php
// modules/User/Application/Command/UpdateUserAvatar/UpdateUserAvatarCommand.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateUserAvatar;

use Illuminate\Http\UploadedFile;
use Modules\User\Domain\ValueObject\UserId;

final readonly class UpdateUserAvatarCommand
{
    public function __construct(
        public UserId $userId,
        public UploadedFile $avatar,
    ) {
    }
}
