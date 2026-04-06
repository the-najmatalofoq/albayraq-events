<?php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\IAM\Domain\Event\UserRegistered;
use Modules\User\Infrastructure\Listener\CreateJoinRequestOnUserRegistered;
use Modules\User\Domain\Repository\{
    UserRepositoryInterface,
    EmployeeProfileRepositoryInterface,
    ContactPhoneRepositoryInterface,
    BankDetailRepositoryInterface,
    UserJoinRequestRepositoryInterface,
    EmployeeNationalityRepositoryInterface,
};
use Modules\User\Infrastructure\Persistence\Eloquent\Repositories\{
    EloquentUserRepository,
    EloquentEmployeeProfileRepository,
    EloquentContactPhoneRepository,
    EloquentBankDetailRepository,
    EloquentUserJoinRequestRepository,
    EloquentEmployeeNationalityRepository,
};

final class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(EmployeeProfileRepositoryInterface::class, EloquentEmployeeProfileRepository::class);
        $this->app->bind(ContactPhoneRepositoryInterface::class, EloquentContactPhoneRepository::class);
        $this->app->bind(BankDetailRepositoryInterface::class, EloquentBankDetailRepository::class);
        $this->app->bind(UserJoinRequestRepositoryInterface::class, EloquentUserJoinRequestRepository::class);
        $this->app->bind(EmployeeNationalityRepositoryInterface::class, EloquentEmployeeNationalityRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        $this->registerListeners();
        $this->registerRoutes();
    }

    private function registerListeners(): void
    {
        Event::listen(
            UserRegistered::class,
            CreateJoinRequestOnUserRegistered::class,
        );
    }

    private function registerRoutes(): void
    {
        $sharedMiddleware = ['api', 'auth:api'];
        $basePath = __DIR__ . '/../Routes';

        $entityRoutes = [
            'User' => 'api/me',
            'EmployeeProfile' => 'api/me',
            'BankDetail' => 'api/me',
            'ContactPhone' => 'api/me',
            'UserJoinRequest' => 'api/join-requests',
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
