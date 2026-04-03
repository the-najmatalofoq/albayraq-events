<?php
// modules/IAM/Application/Command/RegisterUser/RegisterContactPhone/RegisterContactPhoneHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// fix: use Eloquent Model and Eloquent Repositoy Injected here for contact_phones table
final readonly class RegisterContactPhoneHandler
{
    public function handle(RegisterContactPhoneCommand $command): void
    {
        DB::table('contact_phones')->insert([
            'id' => Str::uuid()->toString(),
            'user_id' => $command->userId,
            'label' => $command->label,
            'phone' => $command->phone,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
