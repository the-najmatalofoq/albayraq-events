<?php
// modules/User/Presentation/Http/Presenter/UserPresenter.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

final class UserPresenter
{
    public static function basic(object $user): array
    {
        return [
            'id'    => $user->uuid->value,
            'name'  => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];
    }
}
