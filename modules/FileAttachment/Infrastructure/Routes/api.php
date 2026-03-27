<?php
// modules/FileAttachment/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\FileAttachment\Presentation\Http\Action\UploadFileAction;
use Modules\FileAttachment\Presentation\Http\Action\GetFileAction;

Route::post('/', UploadFileAction::class);
Route::get('/{id}', GetFileAction::class);
