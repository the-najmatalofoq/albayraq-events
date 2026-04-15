<?php
// modules/EventBreakRequest/Presentation/Http/Resources/BreakRequestPresenter.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Presenter;

use Modules\EventBreakRequest\Infrastructure\Persistence\Models\BreakRequestModel;

final class BreakRequestPresenter
{
    public static function fromModel(BreakRequestModel $breakRequest): array
    {
        return [
            'id' => $breakRequest->id,
            'event_id' => $breakRequest->participation->event_id ?? null,
            'employee' => [
                'id' => $breakRequest->requestedBy->id ?? null,
                'name' => static::getLocalizedName($breakRequest->requestedBy->name ?? null),
            ],
            'date' => $breakRequest->date->format('Y-m-d'),
            'start_time' => $breakRequest->start_time,
            'end_time' => $breakRequest->end_time,
            'duration_minutes' => $breakRequest->duration_minutes,
            'status' => $breakRequest->status,
            'requested_by' => [
                'id' => $breakRequest->requestedBy->id ?? null,
                'name' => static::getLocalizedName($breakRequest->requestedBy->name ?? null),
            ],
            'approved_by' => $breakRequest->approvedBy ? [
                'id' => $breakRequest->approvedBy->id,
                'name' => static::getLocalizedName($breakRequest->approvedBy->name),
            ] : null,
            'approved_at' => $breakRequest->approved_at?->format('Y-m-d H:i:s'),
            'rejection_reason' => $breakRequest->rejection_reason,
            'cover_employee' => $breakRequest->coverEmployee ? [
                'id' => $breakRequest->coverEmployee->id,
                'name' => static::getLocalizedName($breakRequest->coverEmployee->name),
            ] : null,
        ];
    }

    private static function getLocalizedName($nameStruct): string
    {
        if (is_array($nameStruct)) {
            $locale = app()->getLocale();
            return $nameStruct[$locale] ?? $nameStruct['en'] ?? $nameStruct['ar'] ?? '';
        }
        return (string) $nameStruct;
    }
}
