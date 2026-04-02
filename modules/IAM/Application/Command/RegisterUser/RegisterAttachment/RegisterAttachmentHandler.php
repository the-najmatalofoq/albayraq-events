<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAttachment/RegisterAttachmentHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAttachment;

use Modules\User\Domain\ValueObject\UserId;
use Modules\FileAttachment\Domain\Repository\FileAttachmentRepositoryInterface;
use Modules\FileAttachment\Domain\FileAttachment;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Illuminate\Support\Facades\Storage;

final readonly class RegisterAttachmentHandler
{
    public function __construct(
        private FileAttachmentRepositoryInterface $attachmentRepository,
    ) {}

    public function handle(RegisterAttachmentCommand $command, UserId $userId): void
    {
        $files = [
            'cv'                => $command->cv,
            'medical_record'    => $command->medical_record,
            'personal_identity' => $command->personal_identity,
        ];

        foreach ($files as $type => $path) {
            if (!$path) {
                continue;
            }

            $attachmentId = $this->attachmentRepository->nextIdentity();

            // Get file size (or 0 if not accessible)
            $fileSize = 0;
            try {
                if (Storage::disk('public')->exists($path)) {
                    $fileSize = (int) Storage::disk('public')->size($path);
                    $fileExtension = pathinfo($path, PATHINFO_EXTENSION);
                }
            } catch (\Exception $e) {
                // Keep 0
            }

            $attachment = FileAttachment::create(
                uuid: $attachmentId,
                attachableId: $userId->value,
                attachableType: UserModel::class,
                filePath: $path,
                fileName: basename($path),
                fileType: $fileExtension,
                fileSize: $fileSize,
                collection: $type // optional collection name
            );

            $this->attachmentRepository->save($attachment);
        }
    }
}
