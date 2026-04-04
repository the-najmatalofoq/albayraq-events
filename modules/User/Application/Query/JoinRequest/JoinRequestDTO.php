<?php

declare(strict_types=1);

namespace Modules\User\Application\Query\JoinRequest;

use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserJoinRequestModel;

final readonly class JoinRequestDTO
{
    public function __construct(
        public string  $id,
        public string  $userId,
        public string  $status,
        public ?string $reviewedBy,
        public ?string $reviewedAt,
        public ?string $notes,
        public string  $createdAt,
        public ?string $updatedAt,
    ) {}

    public static function fromModel(UserJoinRequestModel $model): self
    {
        return new self(
            id:         $model->id,
            userId:     $model->user_id,
            status:     $model->status->value,
            reviewedBy: $model->reviewed_by,
            reviewedAt: $model->reviewed_at?->toIso8601String(),
            notes:      $model->notes,
            createdAt:  $model->created_at->toIso8601String(),
            updatedAt:  $model->updated_at?->toIso8601String(),
        );
    }
}
