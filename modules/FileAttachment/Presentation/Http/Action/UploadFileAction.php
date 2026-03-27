<?php
// modules/FileAttachment/Presentation/Http/Action/UploadFileAction.php
declare(strict_types=1);

namespace Modules\FileAttachment\Presentation\Http\Action;

use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Modules\FileAttachment\Domain\Repository\FileAttachmentRepositoryInterface;
use Modules\FileAttachment\Domain\FileAttachment;
use Modules\FileAttachment\Presentation\Http\Presenter\FileAttachmentPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Support\Facades\Storage;
use Modules\IAM\Domain\ValueObject\UserId;

final readonly class UploadFileAction
{
    public function __construct(
        private FileAttachmentRepositoryInterface $repository,
        private JsonResponder $responder
    ) {
    }

    public function __invoke(ServerRequestInterface $request): mixed
    {
        $uploadedFiles = $request->getUploadedFiles();
        $file = $uploadedFiles['file'] ?? null;

        if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
            return $this->responder->error('No file uploaded or upload error', 400);
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $fileName = Str::uuid() . '.' . $extension;
        $path = 'attachments/' . $fileName;

        Storage::disk('local')->put($path, (string) $file->getStream());

        $id = $this->repository->nextIdentity();
        $attachment = FileAttachment::create(
            uuid: $id,
            originalName: $file->getClientFilename(),
            storagePath: $path,
            mimeType: $file->getClientMediaType(),
            size: $file->getSize(),
            uploaderId: UserId::fromString(auth()->id())
        );

        $this->repository->save($attachment);

        return $this->responder->success(
            data: FileAttachmentPresenter::fromDomain($attachment)
        );
    }
}
