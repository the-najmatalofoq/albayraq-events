# User Module Service Provider Registration

## Service Provider Class

`Modules\User\Infrastructure\Providers\UserServiceProvider`

## Registration Location

`bootstrap/providers.php`

---

## Provider Responsibilities

| Responsibility        | Implementation                                                  |
| --------------------- | --------------------------------------------------------------- |
| **Bind interfaces**   | Binds UserRepositoryInterface to EloquentUserRepository         |
| **Load migrations**   | Loads all migrations from Infrastructure/Persistence/Migrations |
| **Load routes**       | Loads API routes from Infrastructure/Routes/api.php             |
| **Register commands** | Registers console commands (if any)                             |
| **Publish config**    | Publishes user.php config file (optional)                       |

---

## Service Provider Code Structure

```php
final class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository interface to implementation
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );

        // Register other interfaces if needed
        $this->app->bind(
            UserReadRepositoryInterface::class,
            EloquentUserReadRepository::class
        );
    }

    public function boot(): void
    {
        // Load migrations from module directory
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        // Load API routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');

        // Load translations (if any)
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'user');
    }
}
```

---

## Provider Registration Order (Critical)

**MUST be registered FIRST** in `bootstrap/providers.php`:

```php
return [
    // 1. Foundation modules (User first)
    Modules\User\Infrastructure\Providers\UserServiceProvider::class,
    Modules\Shared\Infrastructure\Providers\SharedServiceProvider::class,

    // 2. Catalog modules
    Modules\Role\Infrastructure\Providers\RoleServiceProvider::class,
    Modules\ViolationType\Infrastructure\Providers\ViolationTypeServiceProvider::class,

    // 3. Event modules
    Modules\Event\Infrastructure\Providers\EventServiceProvider::class,

    // 4. Participation modules (depend on User)
    Modules\EventParticipation\Infrastructure\Providers\EventParticipationServiceProvider::class,

    // 5. Operation modules
    Modules\EventContract\Infrastructure\Providers\EventContractServiceProvider::class,
    // ... rest of modules
];
```

---

## Why Order Matters

| If User provider is AFTER another provider | Consequence                                                          |
| ------------------------------------------ | -------------------------------------------------------------------- |
| RoleServiceProvider loads first            | role_user foreign key to users fails (users table doesn't exist yet) |
| EventParticipationProvider loads first     | event_participations.user_id foreign key fails                       |
| Any module with user_id FK loads first     | Migration error, system won't boot                                   |

---

## Deferred Providers

UserServiceProvider is **NOT deferred**. It must load immediately because:

- Authentication needs UserRepositoryInterface on every request
- Middleware may check user existence
- Route binding may resolve User models

---

## Testing Provider Registration

```bash
# Verify provider is registered
php artisan provider:list | grep User

# Expected output:
# Modules\User\Infrastructure\Providers\UserServiceProvider
```

---

## Common Registration Errors

| Error                                                   | Solution                                           |
| ------------------------------------------------------- | -------------------------------------------------- |
| `Class 'UserRepositoryInterface' not found`             | Check use statements, run `composer dump-autoload` |
| `Target class [UserRepositoryInterface] does not exist` | Verify interface file exists in Domain/Repository  |
| `Migration not found`                                   | Check path in loadMigrationsFrom()                 |

---

## Module Activation

Provider automatically registers when Laravel boots. No additional steps required.
