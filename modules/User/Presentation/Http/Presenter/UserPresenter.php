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
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];
    }
}
