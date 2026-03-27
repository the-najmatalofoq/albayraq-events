<?php
// modules/FileAttachment/Presentation/Http/Action/GetFileAction.php
declare(strict_types=1);

namespace Modules\FileAttachment\Presentation\Http\Action;

use Modules\FileAttachment\Domain\Repository\FileAttachmentRepositoryInterface;
use Modules\FileAttachment\Domain\ValueObject\AttachmentId;
use Modules\FileAttachment\Domain\Exception\AttachmentNotFoundException;
use Modules\FileAttachment\Presentation\Http\Presenter\FileAttachmentPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetFileAction
{
    public function __construct(
        private FileAttachmentRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): mixed
    {
        $attachment = $this->repository->findById(AttachmentId::fromString($id));

        if (!$attachment) {
            throw AttachmentNotFoundException::withId(AttachmentId::fromString($id));
        }

        return $this->responder->success(
            data: FileAttachmentPresenter::fromDomain($attachment)
        );
    }
}
