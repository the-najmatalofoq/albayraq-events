<?php
// modules/User/Infrastructure/Providers/UserServiceProvider.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\User\Domain\Repository\{
    UserRepositoryInterface,
    EmployeeProfileRepositoryInterface,
    ContactPhoneRepositoryInterface,
    BankDetailRepositoryInterface,
};
use Modules\User\Infrastructure\Persistence\Eloquent\Repositories\{
    EloquentUserRepository,
    EloquentEmployeeProfileRepository,
    EloquentContactPhoneRepository,
    EloquentBankDetailRepository,
};

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
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        $sharedMiddleware = ['api', 'auth:api'];
        $basePath         = __DIR__ . '/../Routes';

        $entityRoutes = [
            'User'            => 'api/me',
            'EmployeeProfile' => 'api/me',
            'BankDetail'      => 'api/me',
            'ContactPhone'    => 'api/me',
        ];

        foreach ($entityRoutes as $entity => $prefix) {
            $routeFile = "{$basePath}/{$entity}/api.php";

            if (!file_exists($routeFile)) {
                continue;
            }

            Route::prefix($prefix)
                 ->middleware($sharedMiddleware)
                 ->group($routeFile);
        }
    }
}
