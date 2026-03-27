<?php
// modules/FileAttachment/Infrastructure/Providers/FileAttachmentServiceProvider.php
declare(strict_types=1);

namespace Modules\FileAttachment\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\FileAttachment\Domain\Repository\FileAttachmentRepositoryInterface;
use Modules\FileAttachment\Infrastructure\Persistence\Eloquent\EloquentFileAttachmentRepository;

final class FileAttachmentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FileAttachmentRepositoryInterface::class, EloquentFileAttachmentRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/attachments')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
