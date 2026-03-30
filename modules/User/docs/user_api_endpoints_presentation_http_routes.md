# User Module API Endpoints

## Base Path

`/api/v1/users`

## Authentication

- Public routes: `register`, `verify-phone`
- Protected routes: All others require JWT token in `Authorization: Bearer {token}` header

---

## Endpoint Table

| Method | URI                          | Controller Action          | Auth     | Roles Allowed                                          |
| ------ | ---------------------------- | -------------------------- | -------- | ------------------------------------------------------ |
| POST   | `/register`                  | RegisterUserAction         | None     | Public                                                 |
| POST   | `/verify-phone`              | VerifyPhoneAction          | None     | Public                                                 |
| GET    | `/profile`                   | GetProfileAction           | Required | Self                                                   |
| PUT    | `/profile`                   | UpdateProfileAction        | Required | Self                                                   |
| POST   | `/contact-phones`            | AddContactPhoneAction      | Required | Self                                                   |
| DELETE | `/contact-phones/{id}`       | RemoveContactPhoneAction   | Required | Self                                                   |
| GET    | `/bank-details`              | ListBankDetailsAction      | Required | Self                                                   |
| POST   | `/bank-details`              | AddBankDetailAction        | Required | Self                                                   |
| PUT    | `/bank-details/{id}/primary` | SetPrimaryBankDetailAction | Required | Self                                                   |
| DELETE | `/bank-details/{id}`         | RemoveBankDetailAction     | Required | Self                                                   |
| GET    | `/{id}`                      | GetUserAction              | Required | Self, system_controller, general_manager               |
| GET    | `/`                          | ListUsersAction            | Required | system_controller, general_manager, operations_manager |

---

## Request/Response Examples

### POST /register

**Request:**

```json
{
    "name": { "ar": "أحمد محمد", "en": "Ahmed Mohamed" },
    "phone": "+966501234567",
    "email": "ahmed@example.com",
    "password": "SecurePass123",
    "password_confirmation": "SecurePass123"
}
```

````

**Response (201):**

```json
{
    "user_id": "550e8400-e29b-41d4-a716-446655440000",
    "message": "User registered. Awaiting activation."
}
```

### POST /verify-phone

**Request:**

```json
{
    "user_id": "550e8400-e29b-41d4-a716-446655440000",
    "code": "123456"
}
```

**Response (200):**

```json
{
    "message": "Phone verified successfully"
}
```

### GET /profile

**Response (200):**

```json
{
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "name": { "ar": "أحمد محمد", "en": "Ahmed Mohamed" },
    "phone": "+966501234567",
    "email": "ahmed@example.com",
    "is_active": true,
    "phone_verified_at": "2026-03-31T10:00:00Z",
    "profile": {
        "full_name": { "ar": "أحمد محمد علي", "en": "Ahmed Mohamed Ali" },
        "birth_date": "1990-05-15",
        "nationality": "SA",
        "gender": "male"
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

### GET /users (List)

**Query Params:** `?page=1&per_page=15&is_active=true&role=individual`
**Response (200):** Paginated array of user summaries (no sensitive data)

---

## Rate Limits

- `/register`: 5 requests per IP per hour
- `/verify-phone`: 10 requests per user per hour
- All other endpoints: 100 requests per user per minute

---

## Error Responses

| Status | Code     | When                         |
| ------ | -------- | ---------------------------- |
| 401    | -        | Missing or invalid JWT token |
| 403    | USER_004 | Account is inactive          |
| 422    | USER_001 | Phone already registered     |
| 422    | USER_002 | Email already registered     |
| 404    | USER_005 | User not found               |
| 403    | USER_006 | Self-deactivation attempt    |
````
