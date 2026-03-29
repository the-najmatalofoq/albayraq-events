<?php

namespace Modules\AttendanceBarcode\Infrastructure\Persistence\Eloquent;

use DateTimeImmutable;
use Modules\AttendanceBarcode\Domain\AttendanceBarcode;
use Modules\AttendanceBarcode\Domain\Repository\AttendanceBarcodeRepositoryInterface;

class EloquentAttendanceBarcodeRepository implements AttendanceBarcodeRepositoryInterface
{
    public function findById(string $id): ?AttendanceBarcode
    {
        $model = AttendanceBarcodeModel::find($id);
        if (!$model) {
            return null;
        }

        return new AttendanceBarcode(
            $model->id,
            $model->event_participation_id,
            $model->code,
            DateTimeImmutable::createFromMutable($model->generated_at),
            $model->expires_at ? DateTimeImmutable::createFromMutable($model->expires_at) : null,
        );
    }

    public function save(AttendanceBarcode $barcode): void
    {
        $model = AttendanceBarcodeModel::findOrNew($barcode->id);
        $model->event_participation_id = $barcode->eventParticipationId;
        $model->code = $barcode->code;
        $model->generated_at = $barcode->generatedAt->format('Y-m-d H:i:s');
        $model->expires_at = $barcode->expiresAt?->format('Y-m-d H:i:s');
        $model->save();
    }
}
