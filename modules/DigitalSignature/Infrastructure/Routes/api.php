<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\DigitalSignature\Presentation\Http\Action\{
    CreateDigitalSignatureAction,
    UpdateDigitalSignatureAction,
    DeleteDigitalSignatureAction,
    GetAllDigitalSignaturesAction,
    GetAllDigitalSignaturesPaginatedAction,
    GetDigitalSignatureByIdAction,
    GetDigitalSignatureByContractIdAction,
};

Route::middleware(['auth:api'])->group(function () {
    Route::post('/', CreateDigitalSignatureAction::class);
    Route::get('/', GetAllDigitalSignaturesPaginatedAction::class);
    Route::get('/all', GetAllDigitalSignaturesAction::class);
    Route::get('/contract/{contractId}', GetDigitalSignatureByContractIdAction::class);
    Route::get('/{id}', GetDigitalSignatureByIdAction::class);
    Route::put('/{id}', UpdateDigitalSignatureAction::class);
    Route::delete('/{id}', DeleteDigitalSignatureAction::class);
});
