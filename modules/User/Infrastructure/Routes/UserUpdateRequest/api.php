<?php
// modules\User\Infrastructure\Routes\UserUpdateRequest\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\UserUpdateRequest\ListMyUpdateRequestsAction;
use Modules\User\Presentation\Http\Action\UserUpdateRequest\ShowMyUpdateRequestAction;

Route::get('/', ListMyUpdateRequestsAction::class);
Route::get('/{id}', ShowMyUpdateRequestAction::class);
