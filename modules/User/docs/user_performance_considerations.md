# User Module Performance Considerations

## Critical Performance Rules

| Rule                             | Why                    | Impact                              |
| -------------------------------- | ---------------------- | ----------------------------------- |
| Always eager load relationships  | Prevents N+1 queries   | 10x faster for lists                |
| Index phone, email, national_id  | Used for lookups       | Sub-millisecond queries             |
| Cache user profile for 5 minutes | Profile rarely changes | Reduces DB load by 80%              |
| Never load all users             | Use pagination         | Memory overflow risk                |
| Batch operations for seeders     | Reduces query count    | Seeder runs in seconds, not minutes |

---

## Index Strategy

| Table             | Index                 | Type                            |
| ----------------- | --------------------- | ------------------------------- |
| users             | phone                 | UNIQUE                          |
| users             | email                 | UNIQUE                          |
| users             | national_id           | UNIQUE                          |
| users             | is_active, deleted_at | Composite                       |
| employee_profiles | user_id               | FOREIGN KEY                     |
| contact_phones    | user_id               | FOREIGN KEY                     |
| bank_details      | user_id               | FOREIGN KEY                     |
| bank_details      | user_id, is_primary   | Partial (where is_primary=true) |

---

## Caching Strategy

| Cache Key           | TTL        | Invalidated On                              |
| ------------------- | ---------- | ------------------------------------------- |
| user:{id}:profile   | 5 minutes  | Profile update, bank change, contact change |
| user:{phone}:lookup | 1 hour     | User update, phone change                   |
| users:active:list   | 15 minutes | Any user activation/deactivation            |

---

## Query Optimization

### Bad (N+1)

```php
$users = UserModel::all();
foreach ($users as $user) {
    $profile = $user->employeeProfile; // Extra query per user
}
```

### Good (Eager load)

```php
$users = UserModel::with(['employeeProfile', 'bankDetails'])->paginate(20);
```

---

## Pagination Defaults

| Endpoint                | Default per_page | Max per_page |
| ----------------------- | ---------------- | ------------ |
| GET /users              | 15               | 100          |
| GET /users/bank-details | 50               | 200          |

---

## Expected Load

| Metric                     | Value  |
| -------------------------- | ------ |
| Max users                  | 50,000 |
| Concurrent logins          | 5,000  |
| Profile updates per second | 100    |
| Registration per second    | 50     |

---

## Bottlenecks & Solutions

| Bottleneck                  | Solution                             |
| --------------------------- | ------------------------------------ |
| Phone lookup on every login | Cache phone→id mapping               |
| Bank details on payroll day | Read replica for SELECT queries      |
| Soft-deleted user queries   | Always add `whereNull('deleted_at')` |
| JSON name field searches    | Use generated column or full-text    |

---

## Monitoring

Track these metrics in production:

- Time to register (target: <500ms)
- Profile load time (target: <200ms)
- Login lookup time (target: <100ms)
- Cache hit ratio (target: >70%)
