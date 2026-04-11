<?php

namespace Modules\AttendanceBarcode\Domain\Repository;

use Modules\AttendanceBarcode\Domain\AttendanceBarcode;

// fix: use the FilterableRepositoryInterface
interface AttendanceBarcodeRepositoryInterface
{
    public function findById(string $id): ?AttendanceBarcode;

    public function save(AttendanceBarcode $barcode): void;
}
