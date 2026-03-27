<?php
// modules/EventAssetCustody/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventAssetCustody\Presentation\Http\Action\ListEventAssetCustodiesAction;

Route::get('/', ListEventAssetCustodiesAction::class);
