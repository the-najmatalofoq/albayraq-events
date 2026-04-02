<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Service;

use Illuminate\Http\UploadedFile;
use Modules\Shared\Domain\ValueObject\FilePath;

interface FileStorageInterface
{
    public function upload(UploadedFile $file, string $directory): FilePath;
    public function delete(FilePath $filePath): void;
}