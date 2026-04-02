<?php
// modules/User/Infrastructure/Providers/UserServiceProvider.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\User\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use Modules\User\Infrastructure\Persistence\Eloquent\EloquentEmployeeProfileRepository;
use Modules\User\Infrastructure\Persistence\Eloquent\EloquentContactPhoneRepository;
use Modules\User\Infrastructure\Persistence\Eloquent\EloquentBankDetailRepository;

final class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(EmployeeProfileRepositoryInterface::class, EloquentEmployeeProfileRepository::class);
        $this->app->bind(ContactPhoneRepositoryInterface::class, EloquentContactPhoneRepository::class);
        $this->app->bind(BankDetailRepositoryInterface::class, EloquentBankDetailRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/me')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
