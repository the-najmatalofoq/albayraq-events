<?php
// modules/Shared/Infrastructure/Services/FileStorage.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Shared\Domain\Service\FileStorageInterface;
use Modules\Shared\Domain\ValueObject\FilePath;

// fix: make the name of the file better, user the "Service" suffix.
final class FileStorage implements FileStorageInterface
{
    private readonly string $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.upload_disk', 'public');
    }

    public function upload(UploadedFile $file, string $directory): FilePath
    {
        $path = $file->store($directory, $this->disk);

        if ($path === false) {
            // fix: don't use RuntimeException, use custome exceptions that work with bootstrap/app.php
            throw new \RuntimeException("Failed to upload file to {$directory}");
        }

        return new FilePath($path);
    }

    public function uploadForUser(UploadedFile $file, string $userId, string $collection): FilePath
    {
        $directory = "users/{$userId}/{$collection}";

        return $this->upload($file, $directory);
    }

    public function delete(FilePath $filePath): void
    {
        if (Storage::disk($this->disk)->exists($filePath->value)) {
            Storage::disk($this->disk)->delete($filePath->value);
        }
    }

    public function exists(FilePath $filePath): bool
    {
        return Storage::disk($this->disk)->exists($filePath->value);
    }

    public function size(FilePath $filePath): int
    {
        if (!$this->exists($filePath)) {
            return 0;
        }

        return (int) Storage::disk($this->disk)->size($filePath->value);
    }

    public function url(FilePath $filePath): string
    {
        return Storage::disk($this->disk)->url($filePath->value);
    }
}
