<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Service;

use Illuminate\Http\UploadedFile;
use Modules\Shared\Domain\ValueObject\FilePath;

interface FileStorageInterface
{
    /**
     * رفع الملف وإرجاع كائن الـ Value Object
     */
    public function upload(UploadedFile $file, string $directory): FilePath;

    /**
     * حذف ملف موجود
     */
    public function delete(FilePath $filePath): void;
}