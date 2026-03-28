<?php
// modules/User/Presentation/Http/Presenter/UserPresenter.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Domain\User;

final class UserPresenter
{
    public function present(User $user): array
    {
        return [
            'id' => $user->uuid->value,
            'name' => $user->name->toArray(),
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
            'is_active' => $user->isActive,
            'created_at' => $user->createdAt->format(\DateTimeInterface::ATOM),
        ];
    }
}
