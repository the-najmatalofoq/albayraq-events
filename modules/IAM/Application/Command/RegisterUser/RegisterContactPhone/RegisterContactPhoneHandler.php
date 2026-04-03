<?php
// modules/IAM/Application/Command/RegisterUser/RegisterContactPhone/RegisterContactPhoneHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone;

use Modules\User\Infrastructure\Persistence\Eloquent\Models\ContactPhoneModel;
use Illuminate\Support\Str;

final readonly class RegisterContactPhoneHandler
{
    public function handle(RegisterContactPhoneCommand $command): void
    {
        ContactPhoneModel::create([
            'id' => Str::uuid()->toString(),
            'user_id' => $command->userId,
            'relation' => $command->label,
            'phone' => $command->phone,
        ]);
    }
}
