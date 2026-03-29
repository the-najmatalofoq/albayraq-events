<?php

namespace Modules\AttendanceBarcode\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\AttendanceBarcode\Domain\Repository\AttendanceBarcodeRepositoryInterface;
use Modules\AttendanceBarcode\Infrastructure\Persistence\Eloquent\EloquentAttendanceBarcodeRepository;

class AttendanceBarcodeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AttendanceBarcodeRepositoryInterface::class,
            EloquentAttendanceBarcodeRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Persistence/Migrations');
        Route::prefix('api/v1/attendance-barcodes')
            ->middleware(['api'])
            ->group(__DIR__.'/../Routes/api.php');
    }
}
