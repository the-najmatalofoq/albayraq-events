<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Shared\Domain\Service\FileStorageInterface;
use Modules\Shared\Domain\ValueObject\FilePath;

final class FileStorage implements FileStorageInterface
{
    private const DISK = 'public';

    public function upload(UploadedFile $file, string $directory): FilePath
    {
        $path = $file->store($directory, self::DISK);

        return new FilePath($path);
    }

    public function delete(FilePath $filePath): void
    {
        if ($filePath->value && Storage::disk(self::DISK)->exists($filePath->value)) {
            Storage::disk(self::DISK)->delete($filePath->value);
        }
    }
}
