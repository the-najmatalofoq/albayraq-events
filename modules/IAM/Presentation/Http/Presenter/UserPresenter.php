<?php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Presenter;

use Modules\IAM\Domain\User;

final class UserPresenter
{
    /**
     * @param  list<string>  $roleNames
     */
    public static function fromDomain(User $user, array $roleNames = []): array
    {
        return [
            'id' => (string) $user->uuid,
            'roles' => $roleNames,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $user->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
