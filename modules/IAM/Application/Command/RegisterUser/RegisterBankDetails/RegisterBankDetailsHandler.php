<?php
// modules/IAM/Application/Command/RegisterUser/RegisterBankDetails/RegisterBankDetailsHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// fix: use Eloquent Model and Eloquent Repositoy Injected here for bank_details table
final readonly class RegisterBankDetailsHandler
{
    public function handle(RegisterBankDetailsCommand $command): void
    {
        $exists = DB::table('bank_details')->where('iban', $command->iban)->exists();
        if ($exists) {
            return;
        }

        DB::table('bank_details')->insert([
            'id' => Str::uuid()->toString(),
            'user_id' => $command->userId,
            'account_owner' => $command->accountOwner,
            'bank_name' => $command->bankName,
            'iban' => $command->iban,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
