# Notification Module

## Module Purpose

Handles push notifications (FCM), real-time broadcasting (Laravel Reverb), and location tracking. Listens to domain events from other modules and delivers notifications to users via three channels: database (in-app bell), FCM (push to phone), broadcast (live WebSocket). Also manages device tokens for FCM and optional location audit logs.

---

## Table Schema

### `device_tokens`

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| user_id | uuid | FK → users.id, CASCADE DELETE |
| token | string | NOT NULL |
| platform | string | ios / android / web |
| device_name | string | NULLABLE |
| is_active | boolean | DEFAULT: true |
| last_used_at | timestamp | NULLABLE |
| created_at, updated_at | timestamps | |

**Unique:** `(user_id, token)`

### `location_logs`

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| event_participation_id | uuid | FK → event_participations.id, CASCADE DELETE |
| latitude | decimal(10,7) | NOT NULL |
| longitude | decimal(10,7) | NOT NULL |
| accuracy | decimal(5,1) | NULLABLE |
| is_within_geofence | boolean | NOT NULL |
| recorded_at | timestamp | NOT NULL |

**Index:** `(event_participation_id, recorded_at)`

---

## Migration Details

| File | Wave | Order |
|------|------|-------|
| `2026_03_25_112000_create_device_tokens_table.php` | Wave 8 | #41 |
| `2026_03_25_113000_create_location_logs_table.php` | Wave 8 | #43 |

**Note:** Laravel's `notifications` table created via `php artisan notifications:table` (Wave 8, #42)

---

## Relations

- `device_tokens.user_id` → `users.id` (CASCADE DELETE)
- `location_logs.event_participation_id` → `event_participations.id` (CASCADE DELETE)

---

## Execution Order

**Wave 8** — after all other modules (Wave 7 completes first)

**Service Provider:** After all other providers in `bootstrap/providers.php`

---

## What's Needed From Others

| Module | What |
|--------|------|
| User | users table, Notifiable trait |
| Event | geofence validation method |
| All modules | Domain events to listen to |

---

## Domain Entities

### Aggregate Root: `DeviceToken`

**Attributes:** DeviceTokenId, UserId, Token, Platform (ios/android/web), DeviceName, IsActive, LastUsedAt

**Rules:** One token per (user, device); tokens can be revoked (is_active = false)

### Value Object: `LocationLog`

**Attributes:** ParticipationId, Latitude, Longitude, Accuracy, IsWithinGeofence, RecordedAt

**Rules:** Not an aggregate; batch-written to DB via scheduled job

### Repository Interface: `DeviceTokenRepositoryInterface`
- save(), findByUser(), findByToken(), revoke(), revokeAllForUser()

### Domain Events
- `DeviceTokenRegistered` — When new device added
- `DeviceTokenRevoked` — When device removed

---

## CQRS Commands

| Command | Input |
|---------|-------|
| RegisterDeviceToken | user_id, token, platform, device_name |
| RevokeDeviceToken | token_id, user_id |
| RevokeAllUserTokens | user_id |

| Query | Output |
|-------|--------|
| GetUserDevices | user_id → list of tokens |
| GetUnreadNotificationsCount | user_id → integer |

---

## API Endpoints

Base: `/api/v1/me`

| Method | URI | Auth |
|--------|-----|------|
| POST | `/device-tokens` | Required |
| DELETE | `/device-tokens/{id}` | Required |
| GET | `/notifications` | Required |
| PATCH | `/notifications/{id}/read` | Required |
| PATCH | `/notifications/read-all` | Required |
| GET | `/notifications/unread-count` | Required |


**POST /me/device-tokens**

Request:
```json
{
    "token": "fcm_token_string",
    "platform": "android",
    "device_name": "Samsung S23"
}
```

Response:
```json
{
    "id": "uuid",
    "message": "Device registered"
}
```


**GET /me/notifications**

Response: Paginated list of Laravel notifications

Infrastructure Implementation
Eloquent Models
DeviceTokenModel:

Table: device_tokens

Casts: is_active → boolean, last_used_at → datetime

Relationships: user() → BelongsTo

EloquentDeviceTokenRepository: Implements DeviceTokenRepositoryInterface

Notification Classes (23 total)
Location: modules/Notification/Application/Notification/

Notification	Listens To	Channels
ContractSentNotification	ContractSent	database, fcm
ContractAcceptedNotification	ContractAccepted	database, fcm
ContractRejectedNotification	ContractRejected	database, fcm
ViolationReportedNotification	ViolationReported	database, fcm
ViolationApprovedNotification	ViolationApproved	database, fcm
ApplicationAcceptedNotification	ApplicationAccepted	database, fcm
ApplicationRejectedNotification	ApplicationRejected	database, fcm
PositionAnnouncedNotification	PositionAnnounced	database, fcm
TaskAssignedNotification	TaskAssigned	database, fcm
TaskOverdueNotification	TaskOverdue	database, fcm
ReportSubmittedNotification	ReportSubmitted	database
ReportApprovedNotification	ReportApproved	database, fcm
ExpenseSubmittedNotification	ExpenseSubmitted	database
ExpenseApprovedNotification	ExpenseApproved	database, fcm
CustodyHandedOverNotification	CustodyHandedOver	database, fcm
AnnouncementBroadcastNotification	AnnouncementBroadcast	database, fcm, broadcast
EventPublishedNotification	EventPublished	database, fcm
AttendanceRecordedNotification	AttendanceRecorded	database
EvaluationSubmittedNotification	EvaluationSubmitted	database
EvaluationLockedNotification	EvaluationLocked	database
CertificateIssuedNotification	CertificateIssued	database, fcm
EventStatusChangedNotification	EventStatusChanged	database, fcm, broadcast
PositionUpdatedNotification	PositionUpdated	broadcast
Custom FCM Channel
modules/Notification/Infrastructure/Channel/FcmChannel.php:

Fetches active device tokens for user

Sends multicast via Firebase Admin SDK

Logs failures, removes invalid tokens

Event Listeners
modules/Notification/Infrastructure/Listeners/:

23 listener classes, one per notification

Each listener: catches domain event → calls notify() on user model

Broadcasting Channels
modules/Notification/Infrastructure/Broadcasting/channels.php:

user.{userId} → user themselves

event.{eventId} → any role holder in event

event.{eventId}.attendance → supervisors+

event.{eventId}.location → supervisors+ (presence)

event.{eventId}.group.{groupId} → group members

Location Tracking
POST /api/events/{eventId}/location:

Validate participation is active

Check geofence using Event.isWithinGeofence()

Broadcast to event.{eventId}.location channel

Cache in Redis (TTL 60s)

Queue for DB write (every 5 minutes)

Service Provider Registration
Class: Modules\Notification\Infrastructure\Providers\NotificationServiceProvider

Register method: Binds DeviceTokenRepositoryInterface, registers FCM channel

Boot method: Loads migrations, routes, broadcasting channels, event listeners

Position: Last in bootstrap/providers.php

php
$providers = [
    // ... all other modules ...
    Modules\Notification\Infrastructure\Providers\NotificationServiceProvider::class,
];
Dependencies
Composer:

json
{
    "require": {
        "laravel/reverb": "^1.0",
        "kreait/laravel-firebase": "^5.0"
    }
}
Required from others: User (users table, Notifiable trait), Event (geofence method)

Provides to: All modules (notification delivery)

Environment Variables
env
# FCM
FIREBASE_CREDENTIALS=storage/app/firebase-credentials.json

# Reverb
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=eventms
REVERB_APP_KEY=app-key
REVERB_APP_SECRET=app-secret
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
REVERB_SCHEME=https
Commands
bash
# Install Reverb
php artisan install:broadcasting

# Start Reverb server
php artisan reverb:start

# Run queue worker for notifications
php artisan queue:work

# Create notifications table
php artisan notifications:table
php artisan migrate
Next Steps
After Notification module: EventParticipationBadge (Module 19)

Requires: User module to have Notifiable trait added to UserModel

Success: Push notifications work, WebSocket channels authorize, location tracking broadcasts
