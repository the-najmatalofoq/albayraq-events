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
    UserSettingsRepositoryInterface,
    MedicalRecordRepositoryInterface,
};
use Modules\User\Infrastructure\Persistence\Eloquent\Repositories\{
    EloquentUserRepository,
    EloquentEmployeeProfileRepository,
    EloquentContactPhoneRepository,
    EloquentBankDetailRepository,
    EloquentUserJoinRequestRepository,
    EloquentEmployeeNationalityRepository,
    EloquentUserSettingsRepository,
    EloquentMedicalRecordRepository,
};
use Modules\User\Presentation\Http\Middleware\EnsureRoleLevelMiddleware;

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
        $this->app->bind(UserSettingsRepositoryInterface::class, EloquentUserSettingsRepository::class);
        $this->app->bind(MedicalRecordRepositoryInterface::class, EloquentMedicalRecordRepository::class);
    }

    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('role.level', EnsureRoleLevelMiddleware::class);
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        $this->registerListeners();
        $this->registerRoutes();
        require __DIR__ . '/../Broadcasting/channels.php';
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
            'User' => 'api/v1/me',
            'EmployeeProfile' => 'api/v1/me',
            'BankDetail' => 'api/v1/me',
            'ContactPhone' => 'api/v1/me',
            'UserJoinRequest' => 'api/v1/join-requests',
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

        $dashboardRouteFile = "{$basePath}/Crm/api.php";
        if (file_exists($dashboardRouteFile)) {
            Route::prefix('api/v1/crm/users')
                ->middleware(['api', 'auth:api', 'role.level:admin,super-admin'])
                ->group($dashboardRouteFile);
        }
    }
}
