<?php
// modules/User/Application/Command/UpdateUserAvatar/UpdateUserAvatarCommand.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateUserAvatar;

use Illuminate\Http\UploadedFile;

final readonly class UpdateUserAvatarCommand
{
    public function __construct(
        public string $userId,
        public UploadedFile $avatar,
    ) {
    }
}
