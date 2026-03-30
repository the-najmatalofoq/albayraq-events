# User Module Presenters & API Response Format

## Presenter Purpose

Transform domain objects into consistent JSON responses. Presenters are stateless, static classes called by Actions.

---

## Response Structure Convention

All User API responses follow this pattern:

### Single Resource

```json
{
    "id": "uuid",
    "name": {"ar": "string", "en": "string"},
    "phone": "string",
    "email": "string|null",
    "is_active": boolean,
    "phone_verified_at": "ISO8601|null",
    "profile": { ... } | null,
    "contact_phones": [],
    "bank_details": []
}
```

````

### Collection (Paginated)

```json
{
    "data": [],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 42,
        "last_page": 3
    },
    "links": {
        "first": "url",
        "last": "url",
        "prev": "url|null",
        "next": "url|null"
    }
}
```

---

## Presenter Classes

| Presenter                    | Purpose                            | Used In                         |
| ---------------------------- | ---------------------------------- | ------------------------------- |
| **UserPresenter**            | Full user with all relations       | GetProfileAction, GetUserAction |
| **UserSummaryPresenter**     | Lightweight user (id, name, phone) | ListUsersAction                 |
| **EmployeeProfilePresenter** | Profile data only                  | Embedded in UserPresenter       |
| **ContactPhonePresenter**    | Single emergency contact           | Embedded in UserPresenter       |
| **BankDetailPresenter**      | Single bank account                | Embedded in UserPresenter       |

---

## Field Mapping: Domain → API

| Domain Field              | API Field         | Transformation                   |
| ------------------------- | ----------------- | -------------------------------- |
| User.getId()              | id                | toString()                       |
| User.getName()            | name              | toArray() → {ar, en}             |
| User.getPhone()           | phone             | As-is                            |
| User.getEmail()           | email             | As-is or null                    |
| User.isActive()           | is_active         | boolean                          |
| User.getPhoneVerifiedAt() | phone_verified_at | toISOString() or null            |
| User.getEmployeeProfile() | profile           | Pass to EmployeeProfilePresenter |
| User.getContactPhones()   | contact_phones    | Map to ContactPhonePresenter     |
| User.getBankDetails()     | bank_details      | Map to BankDetailPresenter       |

---

## Sensitive Field Exclusion

These domain fields are **NEVER** included in API responses:

- password (hashed or otherwise)
- remember_token
- deleted_at
- internal flags (if any)

---

## Example: UserPresenter Output

```json
{
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "name": { "ar": "أحمد محمد", "en": "Ahmed Mohamed" },
    "phone": "+966501234567",
    "email": "ahmed@example.com",
    "is_active": true,
    "phone_verified_at": "2026-03-31T10:00:00+00:00",
    "profile": {
        "full_name": { "ar": "أحمد محمد علي", "en": "Ahmed Mohamed Ali" },
        "birth_date": "1990-05-15",
        "nationality": "SA",
        "gender": "male",
        "medical_record": null,
        "height": 175,
        "weight": 72
    },
    "contact_phones": [
        {
            "id": "660e8400-e29b-41d4-a716-446655440000",
            "name": "Mohamed Ahmed",
            "phone": "+966501234568",
            "relationship": "Father"
        }
    ],
    "bank_details": [
        {
            "id": "770e8400-e29b-41d4-a716-446655440000",
            "account_owner_name": "Ahmed Mohamed",
            "bank_name": "Al Rajhi Bank",
            "iban": "SA4400000001234567891234",
            "is_primary": true
        }
    ]
}
```

---

## Example: UserSummaryPresenter Output (List View)

```json
{
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "name": { "ar": "أحمد محمد", "en": "Ahmed Mohamed" },
    "phone": "+966501234567",
    "is_active": true
}
```

Note: Email, profile, contacts, bank details excluded for performance and privacy.

---

## ISO8601 Date Format

All timestamps use UTC with Z suffix:

- `created_at`: "2026-03-31T10:00:00+00:00"
- `updated_at`: "2026-03-31T10:00:00+00:00"
- `phone_verified_at`: Same format or null
- `birth_date`: Date only, "1990-05-15"

---

## Content Negotiation

- **Accept header:** `application/json` (required)
- **Content-Type header:** `application/json` for requests
- No XML or other formats supported
````
