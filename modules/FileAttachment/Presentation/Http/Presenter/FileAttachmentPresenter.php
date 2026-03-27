<?php
// modules/FileAttachment/Presentation/Http/Presenter/FileAttachmentPresenter.php
declare(strict_types=1);

namespace Modules\FileAttachment\Presentation\Http\Presenter;

use Modules\FileAttachment\Domain\FileAttachment;
use Illuminate\Support\Facades\Storage;

final class FileAttachmentPresenter
{
    public static function fromDomain(FileAttachment $attachment): array
    {
        return [
            'id' => $attachment->uuid->value,
            'original_name' => $attachment->originalName,
            'mime_type' => $attachment->mimeType,
            'size' => $attachment->size,
            'url' => Storage::url($attachment->storagePath),
            'uploader_id' => $attachment->uploaderId->value,
        ];
    }
}
