<?php
// modules/Role/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Role\Presentation\Http\Action\Dashboard\ListRolesAction;
use Modules\Role\Presentation\Http\Action\Dashboard\ListRolesPaginatedAction;
use Modules\Role\Presentation\Http\Action\Dashboard\CreateRoleAction;
use Modules\Role\Presentation\Http\Action\Dashboard\GetRoleByIdAction;
use Modules\Role\Presentation\Http\Action\Dashboard\UpdateRoleAction;
use Modules\Role\Presentation\Http\Action\Dashboard\DeleteRoleAction;

Route::prefix('dashboard')
    ->middleware(['role.level:admin,super-admin'])
    ->group(function () {
    Route::get('/', ListRolesAction::class);
    Route::get('/paginated', ListRolesPaginatedAction::class);
    Route::post('/', CreateRoleAction::class);
    Route::get('/{id}', GetRoleByIdAction::class);
    Route::put('/{id}', UpdateRoleAction::class);
    Route::delete('/{id}', DeleteRoleAction::class);
});
