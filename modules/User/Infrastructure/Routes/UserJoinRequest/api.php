<?php
// modules\User\Infrastructure\Routes\UserJoinRequest\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\JoinRequest\{
    ListJoinRequestsAction,
    GetAllJoinRequestsAction,
    GetJoinRequestAction,
    ApproveJoinRequestAction,
    ToggleJoinRequestStatusAction,
    DeleteJoinRequestAction,
};

Route::get('/all', GetAllJoinRequestsAction::class);
Route::get('/', ListJoinRequestsAction::class);
Route::get('/{id}', GetJoinRequestAction::class);
Route::patch('/{id}/approve', ApproveJoinRequestAction::class);
Route::patch('/{id}/toggle-status', ToggleJoinRequestStatusAction::class);
Route::delete('/{id}', DeleteJoinRequestAction::class);
