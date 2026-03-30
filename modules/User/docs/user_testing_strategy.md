# User Module Testing Strategy

## Test Types

| Type        | Location                 | Purpose                           | Speed  |
| ----------- | ------------------------ | --------------------------------- | ------ |
| Unit        | `tests/Unit/User`        | Test domain logic in isolation    | Fast   |
| Feature     | `tests/Feature/User`     | Test HTTP endpoints with database | Slow   |
| Integration | `tests/Integration/User` | Test module interaction           | Medium |

---

## Unit Tests (Domain Layer)

### What to Test

- User aggregate business rules (activation, deactivation, verification)
- Value object validation (UserId, TranslatableText, EmployeeProfile)
- Domain event emission

### What NOT to Test

- Database operations (use feature tests)
- HTTP layer (use feature tests)
- Repository implementation (use feature tests)

### Key Test Cases

| Test                           | Expected Behavior                           |
| ------------------------------ | ------------------------------------------- |
| User can be registered         | is_active = false, phone_verified_at = null |
| Activate inactive user         | is_active becomes true, emits UserActivated |
| Activate active user           | Throws UserAlreadyActiveException           |
| Deactivate active user         | is_active becomes false                     |
| Self deactivation              | Throws SelfDeactivationException            |
| Verify unverified phone        | Sets timestamp, emits PhoneVerified         |
| Verify verified phone          | Throws PhoneAlreadyVerifiedException        |
| Add duplicate contact phone    | Throws DuplicateContactPhoneException       |
| Add second primary bank detail | First is demoted to non-primary             |
| Remove only bank detail        | Throws exception (cannot remove last)       |

---

## Feature Tests (HTTP Layer)

### What to Test

- API endpoint responses
- Request validation
- Authentication and authorization
- Database persistence

### Key Test Cases

| Endpoint                       | Test Scenario                             |
| ------------------------------ | ----------------------------------------- |
| POST /register                 | Valid data → 201, user created            |
| POST /register                 | Duplicate phone → 422 with USER_001       |
| POST /verify-phone             | Valid code → 200, verified_at set         |
| GET /profile                   | Authenticated → 200 with user data        |
| GET /profile                   | Unauthenticated → 401                     |
| PUT /profile                   | Update name → 200, changes persisted      |
| POST /bank-details             | Add account → 201, bank detail saved      |
| PUT /bank-details/{id}/primary | Change primary → 200, old primary demoted |
| GET /users                     | Admin role → 200 with paginated list      |
| GET /users                     | Individual role → 403                     |

---

## Integration Tests

### What to Test

- User + Role module (role assignment persists)
- User + EventParticipation (participation created)
- User + EventContract (contract belongs to user)

### Example Scenarios

- User registered → Role module assigns INDIVIDUAL role automatically
- User activated → EventParticipation module can create participations
- User deleted with participations → RESTRICT prevents deletion

---

## Test Database

```bash
# Run tests with dedicated test database
php artisan test --env=testing

# Test database config (.env.testing)
DB_CONNECTION=mysql
DB_DATABASE=eventms_test
DB_USERNAME=root
DB_PASSWORD=
```

---

## Test Fixtures

### UserFactory

```php
// Creates UserModel for feature tests
UserModel::factory()->create([
    'phone' => '+966501234567',
    'is_active' => true
]);
```

### Seeder Helper

```php
// Creates domain User aggregate for unit tests
$user = UserSeeder::createWorker([
    'phone' => '+966509999999'
]);
```

---

## Coverage Targets

| Layer                  | Minimum Coverage |
| ---------------------- | ---------------- |
| Domain                 | 90%              |
| Application (Commands) | 80%              |
| Infrastructure         | 70%              |
| Presentation           | 70%              |

---

## Running Tests

```bash
# All User module tests
php artisan test --filter=User

# Unit tests only
php artisan test tests/Unit/User

# Feature tests only
php artisan test tests/Feature/User

# Single test
php artisan test --filter=test_user_can_register
```

---

## Continuous Integration

Tests run automatically on:

- Pull request to main branch
- Push to development branch
- Nightly scheduled run

**Blocking:** Any failing User module test blocks deployment.

```

```
