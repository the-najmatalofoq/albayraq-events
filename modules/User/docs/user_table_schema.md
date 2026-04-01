# User Module Table Schema

## Table: `users`

The core authentication and identity table. Every system user has one record here.

| Column              | Type         | Constraints      | Default           | Description                                       |
| ------------------- | ------------ | ---------------- | ----------------- | ------------------------------------------------- |
| `id`                | uuid         | PRIMARY KEY      | UUID v4           | Auto-generated unique identifier                  |
| `name`              | json         | NOT NULL         | -                 | Multi-language name `{"ar": "اسم", "en": "Name"}` |
| `email`             | varchar(255) | UNIQUE, NULLABLE | NULL              | Optional email for login/recovery                 |
| `phone`             | varchar(255) | UNIQUE, NOT NULL | -                 | Primary login identifier (E.164 format)           |
| `national_id`       | varchar(255) | UNIQUE, NULLABLE | NULL              | National ID / SSN / Civil ID                      |
| `password`          | varchar(255) | NOT NULL         | -                 | Bcrypt-hashed password                            |
| `avatar`            | varchar(255) | NULLABLE         | NULL              | Path to profile picture                           |
| `is_active`         | boolean      | NOT NULL         | false             | Account active status                             |
| `phone_verified_at` | timestamp    | NULLABLE         | NULL              | When phone was OTP-verified                       |
| `remember_token`    | varchar(100) | NULLABLE         | NULL              | Laravel "remember me" token                       |
| `created_at`        | timestamp    | NOT NULL         | CURRENT_TIMESTAMP | Record creation time                              |
| `updated_at`        | timestamp    | NOT NULL         | CURRENT_TIMESTAMP | Last update time                                  |
| `deleted_at`        | timestamp    | NULLABLE         | NULL              | Soft delete timestamp                             |

### Indexes

```sql
CREATE INDEX idx_users_phone ON users(phone);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_national_id ON users(national_id);
CREATE INDEX idx_users_is_active ON users(is_active);
CREATE INDEX idx_users_deleted_at ON users(deleted_at);
```

### Validation Rules

```php
// Phone: E.164 international format
// Examples: +966501234567, +14155552671
'regex:/^\+[1-9]\d{1,14}$/'

// Email: standard email format
'email:rfc,dns'

// National ID: varies by country (no format enforcement in MVP)
```

---

## Table: `employee_profiles`

Extended personal information for workers. One-to-one with users table.

| Column           | Type         | Constraints                                     | Default           | Description                                        |
| ---------------- | ------------ | ----------------------------------------------- | ----------------- | -------------------------------------------------- |
| `id`             | uuid         | PRIMARY KEY                                     | UUID v4           | Auto-generated unique identifier                   |
| `user_id`        | uuid         | FOREIGN KEY → users(id), UNIQUE, CASCADE DELETE | -                 | Links to user account                              |
| `full_name`      | json         | NULLABLE                                        | NULL              | Full name in Arabic/English `{"ar": "", "en": ""}` |
| `birth_date`     | date         | NULLABLE                                        | NULL              | Date of birth (YYYY-MM-DD)                         |
| `nationality`    | varchar(255) | NULLABLE                                        | NULL              | Country code (SA, EG, US, etc.)                    |
| `gender`         | varchar(50)  | NULLABLE                                        | NULL              | Enum: male, female, other                          |
| `medical_record` | text         | NULLABLE                                        | NULL              | Allergies, conditions, medications                 |
| `height`         | smallint     | NULLABLE                                        | NULL              | Height in centimeters (100-250 cm)                 |
| `weight`         | smallint     | NULLABLE                                        | NULL              | Weight in kilograms (30-300 kg)                    |
| `created_at`     | timestamp    | NOT NULL                                        | CURRENT_TIMESTAMP | Record creation time                               |
| `updated_at`     | timestamp    | NOT NULL                                        | CURRENT_TIMESTAMP | Last update time                                   |

### Indexes

```sql
CREATE INDEX idx_employee_profiles_user_id ON employee_profiles(user_id);
CREATE INDEX idx_employee_profiles_birth_date ON employee_profiles(birth_date);
CREATE INDEX idx_employee_profiles_nationality ON employee_profiles(nationality);
```

### Validation Rules

```php
'gender' => 'in:male,female,other'
'height' => 'integer|min:100|max:250'
'weight' => 'integer|min:30|max:300'
'birth_date' => 'date|before:today|after:1900-01-01'
```

---

## Table: `contact_phones`

Emergency contact persons for users. One-to-many with users table.

| Column         | Type         | Constraints                             | Default           | Description                            |
| -------------- | ------------ | --------------------------------------- | ----------------- | -------------------------------------- |
| `id`           | uuid         | PRIMARY KEY                             | UUID v4           | Auto-generated unique identifier       |
| `user_id`      | uuid         | FOREIGN KEY → users(id), CASCADE DELETE | -                 | Links to user account                  |
| `name`         | varchar(255) | NOT NULL                                | -                 | Contact person's full name             |
| `phone`        | varchar(255) | NOT NULL                                | -                 | Contact phone number (E.164 format)    |
| `relationship` | varchar(255) | NULLABLE                                | NULL              | Father, Mother, Spouse, Sibling, Other |
| `created_at`   | timestamp    | NOT NULL                                | CURRENT_TIMESTAMP | Record creation time                   |
| `updated_at`   | timestamp    | NOT NULL                                | CURRENT_TIMESTAMP | Last update time                       |

### Indexes

```sql
CREATE INDEX idx_contact_phones_user_id ON contact_phones(user_id);
CREATE INDEX idx_contact_phones_phone ON contact_phones(phone);
```

### Validation Rules

```php
'name' => 'required|string|max:255'
'phone' => 'required|regex:/^\+[1-9]\d{1,14}$/'
'relationship' => 'nullable|string|in:Father,Mother,Spouse,Sibling,Other'
```

---

## Table: `bank_details`

Bank account information for payroll. One-to-many with users table.

| Column               | Type         | Constraints                             | Default           | Description                         |
| -------------------- | ------------ | --------------------------------------- | ----------------- | ----------------------------------- |
| `id`                 | uuid         | PRIMARY KEY                             | UUID v4           | Auto-generated unique identifier    |
| `user_id`            | uuid         | FOREIGN KEY → users(id), CASCADE DELETE | -                 | Links to user account               |
| `account_owner_name` | varchar(255) | NOT NULL                                | -                 | Name exactly as on bank account     |
| `bank_name`          | varchar(255) | NOT NULL                                | -                 | Financial institution name          |
| `iban`               | varchar(255) | NOT NULL                                | -                 | International Bank Account Number   |
| `is_primary`         | boolean      | NOT NULL                                | true              | Primary account for salary payments |
| `created_at`         | timestamp    | NOT NULL                                | CURRENT_TIMESTAMP | Record creation time                |
| `updated_at`         | timestamp    | NOT NULL                                | CURRENT_TIMESTAMP | Last update time                    |

### Indexes

```sql
CREATE INDEX idx_bank_details_user_id ON bank_details(user_id);
CREATE INDEX idx_bank_details_iban ON bank_details(iban);
CREATE INDEX idx_bank_details_is_primary ON bank_details(is_primary);
CREATE UNIQUE INDEX idx_bank_details_user_primary ON bank_details(user_id) WHERE is_primary = true;
```

### Validation Rules

```php
'account_owner_name' => 'required|string|max:255'
'bank_name' => 'required|string|max:255'
'iban' => 'required|string|regex:/^[A-Z]{2}[0-9A-Z]{4,30}$/'  // IBAN format
'is_primary' => 'boolean'
```

### Business Rules

- A user can have multiple bank accounts
- Only ONE account per user can have `is_primary = true`
- The `is_primary` flag determines which account receives salary
- Deleting a primary account: system should auto-assign another primary or set `is_primary = false` on remaining

---

## Table Relationships Diagram

```
┌─────────────────┐
│     users       │
│  ┌───────────┐  │
│  │ id (PK)   │  │
│  │ name      │  │
│  │ phone     │  │
│  │ email     │  │
│  │ ...       │  │
│  └───────────┘  │
└────────┬────────┘
         │
         │ CASCADE DELETE
         │
         ▼
┌─────────────────────┐
│ employee_profiles   │
│  ┌───────────────┐  │
│  │ id (PK)       │  │
│  │ user_id (FK)  │  │
│  │ full_name     │  │
│  │ birth_date    │  │
│  │ nationality   │  │
│  │ ...           │  │
│  └───────────────┘  │
└─────────────────────┘

┌─────────────────┐
│     users       │
│  ┌───────────┐  │
│  │ id (PK)   │  │
│  └───────────┘  │
└────────┬────────┘
         │
         │ CASCADE DELETE
         │
         ▼
┌─────────────────┐
│ contact_phones  │
│  ┌───────────┐  │
│  │ id (PK)   │  │
│  │ user_id(FK)│  │
│  │ name      │  │
│  │ phone     │  │
│  │ relation  │  │
│  └───────────┘  │
└─────────────────┘

┌─────────────────┐
│     users       │
│  ┌───────────┐  │
│  │ id (PK)   │  │
│  └───────────┘  │
└────────┬────────┘
         │
         │ CASCADE DELETE
         │
         ▼
┌─────────────────┐
│  bank_details   │
│  ┌───────────┐  │
│  │ id (PK)   │  │
│  │ user_id(FK)│  │
│  │ iban      │  │
│  │ is_primary│  │
│  │ ...       │  │
│  └───────────┘  │
└─────────────────┘
```

---

## Storage Engine & Charset

```sql
ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_unicode_ci
```

### Why utf8mb4?

- Supports Arabic and English characters
- Supports emoji (if users add them to names)
- Full Unicode support (4-byte characters)

### Why InnoDB?

- Foreign key constraints support
- ACID compliance
- Row-level locking for concurrent operations
- Crash recovery

---

## Sample Data

### users table

```sql
INSERT INTO users (id, name, phone, email, is_active) VALUES
('550e8400-e29b-41d4-a716-446655440000', '{"ar":"مدير النظام","en":"System Controller"}', '+966500000001', 'admin@system.com', true),
('550e8400-e29b-41d4-a716-446655440001', '{"ar":"أحمد محمد","en":"Ahmed Mohamed"}', '+966501234567', 'ahmed@example.com', true),
('550e8400-e29b-41d4-a716-446655440002', '{"ar":"سارة خالد","en":"Sara Khalid"}', '+966507654321', NULL, false);
```

### employee_profiles table

```sql
INSERT INTO employee_profiles (id, user_id, full_name, birth_date, nationality, gender) VALUES
('660e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440001', '{"ar":"أحمد محمد","en":"Ahmed Mohamed"}', '1990-05-15', 'SA', 'male');
```

### contact_phones table

```sql
INSERT INTO contact_phones (id, user_id, name, phone, relationship) VALUES
('770e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440001', 'Mohamed Ahmed', '+966501234568', 'Father');
```

### bank_details table

```sql
INSERT INTO bank_details (id, user_id, account_owner_name, bank_name, iban, is_primary) VALUES
('880e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440001', 'Ahmed Mohamed', 'Al Rajhi Bank', 'SA4400000001234567891234', true);
```

---

## Migration SQL (Raw)

For reference when writing migrations:

```php
// users table
Schema::create('users', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->json('name');
    $table->string('email')->unique()->nullable();
    $table->string('phone')->unique();
    $table->string('national_id')->unique()->nullable();
    $table->string('password');
    $table->string('avatar')->nullable();
    $table->boolean('is_active')->default(false);
    $table->timestamp('phone_verified_at')->nullable();
    $table->string('remember_token', 100)->nullable();
    $table->timestamps();
    $table->softDeletes();
});

// employee_profiles table
Schema::create('employee_profiles', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();\->unique();
    $table->json('full_name')->nullable();
    $table->date('birth_date')->nullable();
    $table->string('nationality')->nullable();
    $table->string('gender', 50)->nullable();
    $table->text('medical_record')->nullable();
    $table->smallInteger('height')->nullable();
    $table->smallInteger('weight')->nullable();
    $table->timestamps();
});

// contact_phones table
Schema::create('contact_phones', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('phone');
    $table->string('relationship')->nullable();
    $table->timestamps();
});

// bank_details table
Schema::create('bank_details', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
    $table->string('account_owner_name');
    $table->string('bank_name');
    $table->string('iban');
    $table->boolean('is_primary')->default(true);
    $table->timestamps();
});
```
