# Real‑time Join Request Approval – Client Integration

When an admin approves a user's join request, the backend broadcasts a `UserJoinRequestApprovedEvent` on a **private channel**.  
The client (Flutter, React, or any frontend) must subscribe to this channel using a valid JWT token to receive the approval in real time.

## Channel & Event Names

| Item          | Value                          |
|---------------|--------------------------------|
| Channel name  | `users.{userId}` (private)     |
| Event name    | `join-request.approved`        |
| Payload       | `{ user_id, message }`         |

> **Private channel** – the client must authenticate via the `/api/broadcasting/auth` endpoint.

## Prerequisites

1. The user must be **registered** and have a valid **JWT token** (even if `is_active = false`).  
2. The token must be sent in the `Authorization: Bearer <token>` header when connecting and subscribing.
3. Laravel Reverb WebSocket server must be running (`php artisan reverb:start`).

## Flutter Integration

Use the [`flutter_reverb`](https://pub.dev/packages/flutter_reverb) package (or any WebSocket client with Laravel Echo protocol).

```dart
import 'package:flutter_reverb/flutter_reverb.dart';

final reverb = ReverbClient(
  host: 'ws://your-domain.com:8080',      // Reverb WebSocket URL
  appKey: 'your-app-key',
  authEndpoint: 'https://your-domain.com/api/broadcasting/auth',
  token: userJwtToken,                    // stored after login/registration
);

// Subscribe to the user's private channel
String userId = 'the-user-id-from-registration-response';
reverb.private(`users.${userId}`).listen('join-request.approved', (data) {
  print('Approval received: $data');
  // Navigate to login/home screen
  Navigator.pushReplacementNamed(context, '/home');
});
```

## React (JavaScript) Integration

Use the `laravel-echo` package with `pusher-js` (configured for Reverb).

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
  broadcaster: 'reverb',
  key: process.env.REVERB_APP_KEY,
  wsHost: process.env.REVERB_HOST,
  wsPort: process.env.REVERB_PORT,
  forceTLS: false,
  authEndpoint: '/api/broadcasting/auth',
  auth: {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`
    }
  }
});

const userId = localStorage.getItem('userId');
echo.private(`users.${userId}`)
  .listen('join-request.approved', (data) => {
    console.log('Approval received:', data);
    // Redirect to login/home
    window.location.href = '/home';
  });
```

## Backend Developer Notes

- The broadcast event is dispatched automatically when `ApproveJoinRequestHandler` runs.
- Make sure the user has a JWT token **before** approval – the client can obtain it via a login endpoint that allows inactive users (but restricts other actions).
- The channel authorization callback in `routes/channels.php` (or inside `UserServiceProvider`) must return `true` only for the authenticated user owning that channel.
- For local development, use `REVERB_HOST=localhost` and `REVERB_PORT=8080`. In production, configure SSL and a secure WebSocket endpoint.

## Troubleshooting

| Symptom | Likely cause |
|---------|---------------|
| 403 on subscription | Missing or invalid JWT token; or channel authorization callback returns false. |
| Event not received | Reverb not running; wrong channel/event name; client not subscribed before approval. |
| Connection refused | Reverb host/port wrong; firewall blocking WebSocket. |

> **Tip:** Use browser DevTools (Network → WS) or Flutter logs to debug WebSocket frames.
