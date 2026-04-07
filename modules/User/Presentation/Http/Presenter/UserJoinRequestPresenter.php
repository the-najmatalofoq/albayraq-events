<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Domain\UserJoinRequest;

final class UserJoinRequestPresenter
{
    /** @param list<UserJoinRequest> $requests */
    public static function collection(array $requests): array
    {
        return array_map(fn($item) => self::fromDomain($item), $requests);
    }

    public static function fromDomain(UserJoinRequest $request): array
    {
        return [
            'id' => $request->uuid->value,
            'user_id' => $request->userId->value,
            'status' => $request->status->value,
            'reviewed_by' => $request->reviewedBy,
            'reviewed_at' => $request->reviewedAt?->format('Y-m-d H:i:s'),
            'notes' => $request->notes,
            'created_at' => $request->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
