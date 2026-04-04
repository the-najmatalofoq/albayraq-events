<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\UpdateBankDetailsAction;

Route::patch('/bank', UpdateBankDetailsAction::class);
