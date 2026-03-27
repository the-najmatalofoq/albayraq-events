<?php
// modules/ContractRejectionReason/Infrastructure/Providers/ContractRejectionReasonServiceProvider.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\ContractRejectionReason\Domain\Repository\ContractRejectionReasonRepositoryInterface;
use Modules\ContractRejectionReason\Infrastructure\Persistence\Eloquent\EloquentContractRejectionReasonRepository;

final class ContractRejectionReasonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContractRejectionReasonRepositoryInterface::class, EloquentContractRejectionReasonRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/contract-rejection-reasons')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
