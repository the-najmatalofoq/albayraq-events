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
use Modules\User\Domain\ValueObject\UserId;

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
        $id = $this->repository->nextIdentity();
        $fileName = $id->value . '.' . $extension;
        $path = 'attachments/' . $fileName;

        Storage::disk('local')->put($path, (string) $file->getStream());

        $attachment = FileAttachment::create(
            uuid: $id,
            attachableId: (string) auth()->id(),
            attachableType: 'user_upload',
            filePath: $path,
            fileName: $file->getClientFilename(),
            fileType: $file->getClientMediaType(),
            fileSize: $file->getSize(),
        );

        $this->repository->save($attachment);

        return $this->responder->success(
            data: FileAttachmentPresenter::fromDomain($attachment)
        );
    }
}
