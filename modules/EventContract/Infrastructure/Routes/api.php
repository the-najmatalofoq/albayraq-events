<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventContract\Presentation\Http\Action\ListAllEventContractPaginatedAction;

Route::get('/', ListAllEventContractPaginatedAction::class);