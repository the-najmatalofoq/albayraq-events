<?php
// resources/lang/en/messages.php
return [
    'errors' => [
        'validation_failed' => 'The given data was invalid.',
        'unauthenticated' => 'You are not authenticated.',
        'forbidden' => 'You do not have permission to perform this action.',
        'not_found' => 'The requested resource was not found.',
        'token_mismatch' => 'Your session has expired. Please login again.',
        'too_many_requests' => 'Too many requests. Please try again later.',
        'server_error' => 'An internal server error occurred.',
        'service_unavailable' => 'The service is temporarily unavailable.',
        'user_already_exists' => 'A user with this email already exists.',
        'invalid_credentials' => 'Invalid email or password.',
    ],
    'auth' => [
        'login_success' => 'Logged in successfully.',
        'logged_out' => 'Logged out successfully.',
        'registration_success' => 'Registration successful.',
        'token_mismatch' => 'Session expired. Please login again.',
    ],
    'event' => [
        'created' => 'Event created successfully.',
        'updated' => 'Event updated successfully.',
        'deleted' => 'Event deleted successfully.',
        'not_found' => 'Event not found.',
    ],
];
