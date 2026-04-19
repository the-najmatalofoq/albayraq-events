<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Event;

use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Illuminate\Foundation\Events\Dispatchable;

final class UserLoggedIntoNewDevice
{
    use Dispatchable;

    public function __construct(
        public readonly UserModel $user
    ) {}
}
