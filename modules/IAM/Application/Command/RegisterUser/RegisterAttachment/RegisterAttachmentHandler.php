<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAttachment/RegisterAttachmentHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAttachment;

use Modules\Shared\Domain\Service\FileStorageInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// fix: use Eloquent Model and Eloquent Repositoy Injected here for file_attachments table
final readonly class RegisterAttachmentHandler
{
    public function __construct(
        private FileStorageInterface $fileStorage,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(RegisterAttachmentCommand $command): void
    {
        $filePath = $this->fileStorage->uploadForUser(
            $command->file,
            $command->userId,
            $command->collection
        );

        if ($command->collection === 'avatar') {
            $user = $this->userRepository->findById(new UserId($command->userId));
            if ($user) {
                $user->updateAvatar($filePath);
                $this->userRepository->save($user);
            }
        } else {
            DB::table('file_attachments')->insert([
                'id' => Str::uuid()->toString(),
                'attachable_id' => $command->userId,
                'attachable_type' => 'user',
                'collection' => $command->collection,
                'path' => $filePath->value,
                'file_name' => $command->file->getClientOriginalName(),
                'mime_type' => $command->file->getClientMimeType(),
                'size' => $command->file->getSize(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
