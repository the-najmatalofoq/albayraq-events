<?php
// modules/User/Presentation/Http/Presenter/UserPresenter.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Domain\User;

final class UserPresenter
{
    public static function fromDomain(User $user): array
    {
        return [
            'id' => $user->uuid->value,
            'name' => $user->name->getFor(app()->getLocale()),
            'email' => $user->email,
            'phone' => $user->phone->value,
            'avatar' => $user->avatar?->value,
            'national_id' => $user->nationalId,
            'is_active' => $user->isActive,
            'created_at' => \Carbon\Carbon::instance($user->createdAt)->toIso8601String(),
        ];
    }
}
