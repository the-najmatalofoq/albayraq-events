<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAttachment/RegisterAttachmentHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAttachment;

use Modules\Shared\Domain\Service\FileStorageInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\FileAttachment\Domain\Repository\FileAttachmentRepositoryInterface;
use Modules\FileAttachment\Domain\FileAttachment;
use Modules\Shared\Domain\ValueObject\FilePath;

final readonly class RegisterAttachmentHandler
{
    public function __construct(
        private FileStorageInterface $fileStorage,
        private UserRepositoryInterface $userRepository,
        private EmployeeProfileRepositoryInterface $profileRepository,
        private FileAttachmentRepositoryInterface $attachmentRepository,
    ) {}

    public function handle(RegisterAttachmentCommand $command): ?FilePath
    {
        $userId = $command->userId;

        $profile = $this->profileRepository->findByUserId($userId);
        if (!$profile) {
            return null;
        }

        $filePath = $this->fileStorage->uploadForUser(
            $command->file,
            $userId,
            $command->collection
        );

        $attachment = FileAttachment::create(
            uuid: $this->attachmentRepository->nextIdentity(),
            attachableId: $profile->uuid->value,
            attachableType: 'employee_profile',
            filePath: $filePath->value,
            fileName: $command->file->getClientOriginalName(),
            fileType: $command->file->getClientMimeType(),
            fileSize: $command->file->getSize(),
            collection: $command->collection
        );

        $this->attachmentRepository->save($attachment);

        return $filePath;
    }
}
