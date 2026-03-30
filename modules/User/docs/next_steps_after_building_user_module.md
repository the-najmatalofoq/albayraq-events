# Next Steps After Building User Module

## Pre-Flight Checklist

Verify these before moving to next module:

- [ ] Migrations ran successfully (4 tables created)
- [ ] UserSeeder executed (at least 1 active user exists)
- [ ] UserServiceProvider registered in bootstrap/providers.php
- [ ] Authentication works (can register, receive JWT)
- [ ] All User tests pass (unit + feature)
- [ ] API endpoints return expected responses

---

## Immediate Next Module: Role

**Why Role module next?**

- Role module depends on users table (role_user.user_id foreign key)
- Users need roles for authorization
- Default INDIVIDUAL role must be assigned during registration

**Build Order:**

```

User (complete) → Role (next) → Event (third)

```

---

## Commands to Run

```bash
# 1. Verify User module is healthy
php artisan migrate:status | grep users
php artisan test --filter=User

# 2. Create Role module structure
php artisan module:make Role

# 3. Build Role module migrations
# (depends on users table)

# 4. Register RoleServiceProvider AFTER UserServiceProvider
```

---

## What Role Module Needs From User

| Need                    | Provided By                                   |
| ----------------------- | --------------------------------------------- |
| users table             | User migration #1                             |
| UserRepositoryInterface | To fetch users for assignment                 |
| UserId value object     | To type-hint user identifiers                 |
| User domain events      | To auto-assign default role on UserRegistered |

---

## Integration Point

When Role module is built, test this flow:

```
User registers
    ↓
UserRegistered event dispatched
    ↓
Role module listener: AssignDefaultRole
    ↓
INDIVIDUAL role assigned via role_user pivot
    ↓
User can now be authorized
```

---

## Timeline Estimate

| Task                     | Hours     |
| ------------------------ | --------- |
| Role module build        | 2-3 hours |
| Event module build       | 3-4 hours |
| EventParticipation build | 4-5 hours |
| Full system integration  | 1 day     |

---

## Success Criteria for Next Phase

After building User + Role modules:

- [ ] User can register and receive INDIVIDUAL role
- [ ] System controller can assign global roles
- [ ] Authorization middleware works
- [ ] role_user pivot table populated correctly

---

## Rollback Plan

If User module has issues:

```bash
# Rollback all User migrations
php artisan migrate:rollback --path=modules/User/Infrastructure/Persistence/Migrations/

# Remove provider from bootstrap/providers.php

# Delete module directory (if needed)
rm -rf modules/User
```

---

## Documentation Handoff

All User module docs complete at:
`modules/User/docs/*.md` (21 files)

Next module documentation will follow same structure:
`modules/Role/docs/*.md`
