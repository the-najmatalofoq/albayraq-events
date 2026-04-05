<?php
// modules/Shared/Infrastructure/Services/FileStorage.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Shared\Domain\Service\FileStorageInterface;
use Modules\Shared\Domain\ValueObject\FilePath;
use Modules\Shared\Domain\Exception\FileUploadException;
use Modules\User\Domain\ValueObject\UserId;

final class FileStorage implements FileStorageInterface
{
    private string $disk;

    public function __construct()
    {
        $this->disk = (string) config('filesystems.upload_disk', 'public');
    }

    public function upload(UploadedFile $file, string $directory): FilePath
    {
        $path = $file->store($directory, $this->disk);

        if (!$path) {
            throw FileUploadException::failed('Could not store file.');
        }

        return new FilePath($path);
    }

    public function uploadForUser(UploadedFile $file, UserId $userId, string $collection): FilePath
    {
        $directory = "users/{$userId->value}/{$collection}";
        return $this->upload($file, $directory);
    }

    public function delete(FilePath $path): void
    {
        Storage::disk($this->disk)->delete($path->value);
    }

    public function exists(FilePath $path): bool
    {
        return Storage::disk($this->disk)->exists($path->value);
    }

    public function size(FilePath $path): int
    {
        return Storage::disk($this->disk)->size($path->value);
    }

    public function url(FilePath $path): string
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
        $storage = Storage::disk($this->disk);

        return $storage->url($path->value);
    }
}
