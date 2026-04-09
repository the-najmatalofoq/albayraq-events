<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard\ListContractRejectionReasonsAction;
use Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard\ListContractRejectionReasonsPaginatedAction;
use Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard\CreateContractRejectionReasonAction;
use Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard\GetContractRejectionReasonByIdAction;
use Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard\UpdateContractRejectionReasonAction;
use Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard\DeleteContractRejectionReasonAction;

Route::prefix('dashboard/contract-rejection-reasons')
    ->middleware(['role.level:admin,super-admin'])
    ->group(function () {
        Route::get('/', ListContractRejectionReasonsAction::class);
        Route::get('/paginated', ListContractRejectionReasonsPaginatedAction::class);
        Route::post('/', CreateContractRejectionReasonAction::class);
        Route::get('/{id}', GetContractRejectionReasonByIdAction::class);
        Route::put('/{id}', UpdateContractRejectionReasonAction::class);
        Route::delete('/{id}', DeleteContractRejectionReasonAction::class);
    });
