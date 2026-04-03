<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAttachment/RegisterAttachmentCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAttachment;

use Illuminate\Http\UploadedFile;

final readonly class RegisterAttachmentCommand
{
    public function __construct(
        public string $userId,
        public UploadedFile $file,
        public string $collection,
    ) {}
}
