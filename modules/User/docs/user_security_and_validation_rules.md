# User Module Security & Validation Rules

## Security Rules

| Rule                                          | Enforcement                               |
| --------------------------------------------- | ----------------------------------------- |
| Passwords hashed with bcrypt                  | Cost factor 12                            |
| Passwords never returned in API               | Hidden from UserPresenter                 |
| Phone verification required for sensitive ops | Check email_verified_at before payroll    |
| Inactive users cannot log in                  | is_active=false blocks JWT issuance       |
| Soft-deleted users cannot log in              | deleted_at not null blocks authentication |
| Self-deactivation prohibited                  | Business rule in DeactivateUserHandler    |
| Role check on admin endpoints                 | middleware: role:system_controller        |
| Rate limiting on public endpoints             | 5/hour for register, 10/hour for verify   |

---

## Validation Rules by Field

### User Registration

| Field       | Rules                                             |
| ----------- | ------------------------------------------------- |
| name.ar     | required, string, max:255                         |
| name.en     | required, string, max:255                         |
| phone       | required, unique:users, regex:/^\+[1-9]\d{1,14}$/ |
| email       | nullable, email, unique:users                     |
| national_id | nullable, string, unique:users                    |
| password    | required, min:8, confirmed                        |

### Employee Profile

| Field          | Rules                                          |
| -------------- | ---------------------------------------------- |
| full_name.ar   | nullable, string, max:255                      |
| full_name.en   | nullable, string, max:255                      |
| birth_date     | nullable, date, before:today, after:1900-01-01 |
| nationality    | nullable, string, size:2 (ISO country code)    |
| gender         | nullable, in:male,female,other                 |
| height         | nullable, integer, min:100, max:250            |
| weight         | nullable, integer, min:30, max:300             |
| medical_record | nullable, string, max:5000                     |

### Contact Phone

| Field        | Rules                                           |
| ------------ | ----------------------------------------------- |
| name         | required, string, max:255                       |
| phone        | required, regex:/^\+[1-9]\d{1,14}$/             |
| relationship | nullable, in:Father,Mother,Spouse,Sibling,Other |

### Bank Detail

| Field              | Rules                                      |
| ------------------ | ------------------------------------------ |
| account_owner_name | required, string, max:255                  |
| bank_name          | required, string, max:255                  |
| iban               | required, regex:/^[A-Z]{2}[0-9A-Z]{4,30}$/ |
| is_primary         | boolean                                    |

---

## Unique Constraints (Database Level)

| Table        | Columns         | Enforced By                                    |
| ------------ | --------------- | ---------------------------------------------- |
| users        | phone           | UNIQUE index                                   |
| users        | email           | UNIQUE index (nullable allows multiple nulls)  |
| users        | national_id     | UNIQUE index (nullable allows multiple nulls)  |
| bank_details | (user_id, iban) | Application-level (no duplicate IBAN per user) |

---

## Authorization Matrix

| Action             | Role Required                                          |
| ------------------ | ------------------------------------------------------ |
| Register           | None (public)                                          |
| Verify phone       | None (public, but must own user_id)                    |
| View own profile   | Authenticated user                                     |
| Update own profile | Authenticated user                                     |
| View any user      | system_controller, general_manager                     |
| List all users     | system_controller, general_manager, operations_manager |
| Activate user      | system_controller only                                 |
| Deactivate user    | system_controller only                                 |

---

## Input Sanitization

| Field          | Sanitization                                |
| -------------- | ------------------------------------------- |
| name (ar/en)   | Strip tags, trim whitespace                 |
| email          | Lowercase, trim                             |
| phone          | Remove spaces, keep + prefix                |
| national_id    | Trim, uppercase                             |
| medical_record | Escape HTML entities (stored as plain text) |

---

## Error Response Format

```json
{
    "message": "Validation failed",
    "errors": {
        "phone": ["The phone has already been taken."],
        "email": ["The email must be a valid email address."]
    },
    "code": "USER_001"
}
```

---

## Security Headers (Applied to All User Routes)

- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: DENY`
- `X-XSS-Protection: 1; mode=block`
- `Strict-Transport-Security: max-age=31536000`

```

```
