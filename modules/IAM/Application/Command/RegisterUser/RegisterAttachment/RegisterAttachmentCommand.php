<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAttachment/RegisterAttachmentCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAttachment;

use Illuminate\Http\UploadedFile;
use Modules\User\Domain\ValueObject\UserId;

final readonly class RegisterAttachmentCommand
{
    public function __construct(
        public UserId $userId,
        public UploadedFile $file,
        public string $collection,
    ) {}
}
