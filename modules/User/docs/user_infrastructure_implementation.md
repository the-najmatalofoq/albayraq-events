# User Module Infrastructure Implementation

## Layer Purpose

Implements the interfaces defined in Domain using Laravel's Eloquent ORM. Contains all database-specific code.

---

## Components

| Component               | Responsibility                                       |
| ----------------------- | ---------------------------------------------------- |
| **Eloquent Models**     | Map database tables to PHP objects                   |
| **Eloquent Repository** | Implements UserRepositoryInterface using Eloquent    |
| **Reflector**           | Converts between Domain entities and Eloquent models |
| **Migrations**          | Database schema definition                           |
| **Seeders**             | Test and development data                            |

---

## Eloquent Models

| Model                | Table             | Primary Key | Traits                                 |
| -------------------- | ----------------- | ----------- | -------------------------------------- |
| UserModel            | users             | id (uuid)   | HasUuids, SoftDeletes, Authenticatable |
| EmployeeProfileModel | employee_profiles | id (uuid)   | HasUuids                               |
| ContactPhoneModel    | contact_phones    | id (uuid)   | HasUuids                               |
| BankDetailModel      | bank_details      | id (uuid)   | HasUuids                               |

---

## Key Model Features

### UserModel

- **Hidden fields:** password, remember_token (never in JSON)
- **Casts:** name → array, is_active → boolean, phone_verified_at → datetime, deleted_at → datetime
- **Relationships:** employeeProfile (HasOne), contactPhones (HasMany), bankDetails (HasMany)

### EmployeeProfileModel

- **Casts:** full_name → array, birth_date → date
- **Belongs To:** user

### ContactPhoneModel

- **No special casts** (all strings)
- **Belongs To:** user

### BankDetailModel

- **Casts:** is_primary → boolean
- **Belongs To:** user
- **Unique constraint:** No duplicate IBAN per user (application-level, not database)

---

## Repository Implementation

### EloquentUserRepository

Implements UserRepositoryInterface using Eloquent operations.

**Key Methods Behavior:**

| Method          | Implementation                                                                |
| --------------- | ----------------------------------------------------------------------------- |
| save()          | Update or create using updateOrCreate, save relationships via Reflector       |
| findById()      | UserModel::with(['employeeProfile','contactPhones','bankDetails'])->find($id) |
| findByPhone()   | UserModel::where('phone', $phone)->with(...)->first()                         |
| findAllActive() | UserModel::where('is_active', true)->whereNull('deleted_at')->get()           |
| delete()        | Soft delete: $model->delete()                                                 |
| forceDelete()   | Hard delete with cascade                                                      |

**Transaction Wrapping:**
All save operations wrapped in `DB::transaction()` to ensure atomicity.

---

## Reflector Pattern

### Purpose

Bidirectional conversion between Domain User aggregate and multiple Eloquent models.

### UserReflector Methods

| Method                                | Direction   | Operation                                        |
| ------------------------------------- | ----------- | ------------------------------------------------ |
| reflect(User $user, UserModel $model) | Domain → DB | Maps User aggregate to UserModel + relationships |
| reverse(UserModel $model)             | DB → Domain | Reconstructs User aggregate from models          |

### Why Reflector?

- Domain entity has value objects (TranslatableText, arrays of ContactPhone)
- Eloquent models have JSON casts and separate tables
- Centralizes mapping logic, keeps repositories clean

---

## Migration Files

| Migration                                        | Tables Created    | Dependencies |
| ------------------------------------------------ | ----------------- | ------------ |
| 2026_03_25_100000_create_users_table             | users             | None         |
| 2026_03_25_100100_create_employee_profiles_table | employee_profiles | users        |
| 2026_03_25_100200_create_contact_phones_table    | contact_phones    | users        |
| 2026_03_25_100300_create_bank_details_table      | bank_details      | users        |

---

## Infrastructure Configuration

### Database Connection

Uses default Laravel database connection (configured in `.env`)

### Cache Keys

- `user:{id}:profile` — cached for 5 minutes
- `user:{phone}:lookup` — cached for 1 hour

### Queue Connections

- UserRegistered event → dispatched to `default` queue
- UserActivated event → dispatched to `high` priority queue

---

## Performance Notes

- **Eager loading** required for employeeProfile, contactPhones, bankDetails (prevents N+1)
- **Indexes** added on phone, email, national_id, is_active, deleted_at
- **Batch operations** not needed for MVP (user count < 10,000 expected)

```

```
