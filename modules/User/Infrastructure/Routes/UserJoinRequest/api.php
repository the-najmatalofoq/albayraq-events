<?php
// modules\User\Infrastructure\Routes\UserJoinRequest\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\RejectJoinRequestAction;
use Modules\User\Presentation\Http\Action\JoinRequest\{
    ListJoinRequestsAction,
    GetAllJoinRequestsAction,
    GetJoinRequestAction,
    ApproveJoinRequestAction,
    //  ToggleJoinRequestStatusAction,
    //  DeleteJoinRequestAction,
};

Route::middleware(['role.level:admin,super-admin'])->group(function (): void {
    Route::get('/all', GetAllJoinRequestsAction::class);
    Route::get('/', ListJoinRequestsAction::class);
    Route::get('/{id}', GetJoinRequestAction::class);
    Route::post('/{id}/approve', ApproveJoinRequestAction::class);
    // Route::post('/{id}/reject', RejectJoinRequestAction::class);
});
