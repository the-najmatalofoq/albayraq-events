<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserUpdateRequestModel;

final class UserUpdateRequestPresenter
{
    /**
     * A compact representation for list views.
     */
    public static function fromModel(UserUpdateRequestModel $model): array
    {
        return [
            'id'               => $model->id,
            'user_id'          => $model->user_id,
            'user'             => $model->relationLoaded('user') ? [
                'id'    => $model->user->id,
                'name'  => $model->user->name,
                'phone' => $model->user->phone,
                'email' => $model->user->email,
            ] : null,
            'target_type'      => class_basename($model->target_type),
            'target_id'        => $model->target_id,
            'new_data'         => $model->new_data,
            'status'           => $model->status,
            'rejection_reason' => $model->rejection_reason,
            'reviewed_by'      => $model->reviewed_by,
            'reviewed_at'      => $model->reviewed_at?->format('Y-m-d H:i:s'),
            'created_at'       => $model->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * A detailed representation showing old vs new values for the admin review view.
     */
    public static function withComparison(UserUpdateRequestModel $model): array
    {
        $base = self::fromModel($model);

        // Resolve the target (the current record from the database)
        $target = null;
        $oldData = [];
        $diff = [];

        $targetClass = $model->target_type;
        if (class_exists($targetClass)) {
            $target = $targetClass::find($model->target_id);
        }

        if ($target) {
            // Extract current values only for the fields that will be changed
            $oldData = collect($model->new_data)
                ->keys()
                ->mapWithKeys(fn($key) => [$key => $target->getAttribute($key)])
                ->toArray();

            // Build field-by-field comparison
            foreach ($model->new_data as $field => $newValue) {
                $oldValue = $oldData[$field] ?? null;
                $diff[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                    'changed' => $oldValue != $newValue,
                ];
            }
        }

        $base['comparison'] = [
            'old_data' => $oldData,
            'new_data' => $model->new_data,
            'diff'     => $diff,
        ];

        return $base;
    }
}
