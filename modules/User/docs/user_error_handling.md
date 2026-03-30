# User Module Error Handling

## Error Response Format

```json
{
    "error": {
        "code": "USER_001",
        "message": "Phone number already registered",
        "status": 422
    }
}
```

---

## Error Codes (Save Point)

| Code     | HTTP | Message                         | When                              |
| -------- | ---- | ------------------------------- | --------------------------------- |
| USER_001 | 422  | Phone number already registered | Duplicate phone                   |
| USER_002 | 422  | Email already registered        | Duplicate email                   |
| USER_003 | 422  | National ID already registered  | Duplicate national ID             |
| USER_004 | 403  | Account is inactive             | Login attempt on inactive account |
| USER_005 | 404  | User not found                  | Invalid user ID                   |
| USER_006 | 403  | Cannot deactivate yourself      | Self-deactivation attempt         |
| USER_007 | 422  | Invalid phone format            | Phone not E.164 compliant         |
| USER_008 | 422  | Invalid IBAN format             | IBAN pattern mismatch             |
| USER_009 | 422  | No primary bank account         | Payroll requires primary account  |
| USER_010 | 409  | Cannot remove last bank account | User needs at least one account   |

---

## Exception to Code Mapping

| Exception                    | Code     |
| ---------------------------- | -------- |
| DuplicatePhoneException      | USER_001 |
| DuplicateEmailException      | USER_002 |
| DuplicateNationalIdException | USER_003 |
| InactiveUserException        | USER_004 |
| UserNotFoundException        | USER_005 |
| SelfDeactivationException    | USER_006 |
| InvalidPhoneFormatException  | USER_007 |
| InvalidIbanFormatException   | USER_008 |
| NoPrimaryBankDetailException | USER_009 |
| LastBankDetailException      | USER_010 |

---

## Where to Catch

- **Domain exceptions** → Caught in Handler, converted to error response
- **Validation exceptions** → Caught by Laravel's validation middleware
- **Database exceptions** → Caught in Repository, wrapped as domain exception

---

## Critical Rule

**Never expose database errors to client.** Always convert to USER_xxx codes.

Example:

```php
try {
    $user = $this->repository->findById($id);
} catch (QueryException $e) {
    throw new UserNotFoundException();
}
```

---

## Logging Level

| Code         | Log Level | When                          |
| ------------ | --------- | ----------------------------- |
| USER_001-003 | Info      | Expected duplicate validation |
| USER_004-006 | Warning   | Authentication failure        |
| USER_007-010 | Info      | Format validation             |
| Any uncaught | Emergency | Bug in code                   |

---

## Client Handling

Client should check `error.code` string, not HTTP status alone.

```javascript
if (response.error.code === "USER_004") {
    redirectTo("/account-inactive");
}
```
