<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Domain\UserJoinRequest;

final class UserJoinRequestPresenter
{
    /**
     * @param UserJoinRequest[] $joinRequests
     * @return array<int, array<string, mixed>>
     */
    public function presentCollection(array $joinRequests): array
    {
        return array_map(fn (UserJoinRequest $jr) => $this->present($jr), $joinRequests);
    }

    /**
     * @return array<string, mixed>
     */
    public function present(UserJoinRequest $jr): array
    {
        return [
            'id' => $jr->uuid->value,
            'user_id' => $jr->userId->value,
            'status' => $jr->status->value,
            'reviewed_by' => $jr->reviewedBy,
            'reviewed_at' => $jr->reviewedAt?->format('c'),
            'notes' => $jr->notes,
            'created_at' => $jr->createdAt->format('c'),
            'updated_at' => $jr->updatedAt?->format('c'),
        ];
    }
}
