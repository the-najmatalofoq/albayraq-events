<?php
// modules/Shared/Domain/Service/FileStorageInterface.php
declare(strict_types=1);

namespace Modules\Shared\Domain\Service;

use Illuminate\Http\UploadedFile;
use Modules\Shared\Domain\ValueObject\FilePath;

interface FileStorageInterface
{
    public function upload(UploadedFile $file, string $directory): FilePath;

    public function uploadForUser(UploadedFile $file, string $userId, string $collection): FilePath;

    public function delete(FilePath $filePath): void;

    public function exists(FilePath $filePath): bool;

    public function size(FilePath $filePath): int;

    public function url(FilePath $filePath): string;
}