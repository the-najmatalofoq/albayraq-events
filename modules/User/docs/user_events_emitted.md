# User Module Events Emitted

## Event Summary

| Event             | When                      | Payload                                 | Listeners                                  |
| ----------------- | ------------------------- | --------------------------------------- | ------------------------------------------ |
| UserRegistered    | Registration complete     | user_id, phone, email                   | Send welcome SMS, assign default role      |
| UserActivated     | Admin activates account   | user_id, activated_by                   | Send activation notice, trigger onboarding |
| UserDeactivated   | Admin deactivates account | user_id, deactivated_by                 | Revoke sessions, send notice               |
| PhoneVerified     | OTP verification success  | user_id, verified_at                    | Update status, unlock features             |
| ProfileUpdated    | Profile fields changed    | user_id, changed_fields                 | Update search index                        |
| BankDetailAdded   | New bank account added    | user_id, bank_detail_id, is_primary     | Sync payroll system                        |
| BankDetailRemoved | Bank account removed      | user_id, bank_detail_id, was_primary    | Update payroll                             |
| ContactPhoneAdded | Emergency contact added   | user_id, contact_phone_id, relationship | None (future use)                          |

---

## Event Flow Example (Registration)

```

User registers
↓
UserRegistered event dispatched
↓
Listener 1: SendWelcomeSms → SMS sent to user's phone
Listener 2: AssignDefaultRole → INDIVIDUAL role assigned
Listener 3: LogUserAction → Audit log entry created

```

---

## Queue Configuration

| Event           | Queue   | Priority |
| --------------- | ------- | -------- |
| UserRegistered  | default | Normal   |
| UserActivated   | high    | High     |
| UserDeactivated | high    | High     |
| PhoneVerified   | default | Normal   |
| All others      | low     | Low      |

---

## Event Discovery

Events are dispatched from Command Handlers after successful aggregate save:

```php
// Inside ActivateUserHandler
$user->activate();
$this->userRepository->save($user);

event(new UserActivated(
    userId: $user->getId(),
    activatedBy: $this->activatedBy,
    occurredAt: now()
));
```

---

## Listener Registration

Listeners registered in EventServiceProvider:

```php
protected $listen = [
    UserRegistered::class => [
        SendWelcomeSms::class,
        AssignDefaultRole::class,
    ],
    UserActivated::class => [
        SendActivationNotice::class,
        TriggerOnboardingWorkflow::class,
    ],
];
```

---

## No Event Replay

Events are for real-time notifications only. No event sourcing or replay capability in MVP.
