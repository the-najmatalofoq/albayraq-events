<?php
// modules\User\Infrastructure\Routes\BankDetail\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\BankDetail\{GetBankDetailsAction, UpdateBankDetailsAction};

Route::get('/bank', GetBankDetailsAction::class);
Route::put('/bank', UpdateBankDetailsAction::class);
